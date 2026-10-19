// Test the Git deployment contents, not untracked files in the working tree.
// Usage: node tests/package-form-deployment.test.cjs [--index | <commit>]
const assert = require('node:assert/strict');
const { execFileSync, spawnSync } = require('node:child_process');
const fs = require('node:fs');
const os = require('node:os');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const target = process.argv[2] || 'HEAD';
const git = args => execFileSync('git', args, { cwd: root, encoding: 'utf8' });
const indexed = target === '--index';
const files = new Set(git(indexed ? ['ls-files'] : ['ls-tree', '-r', '--name-only', target]).trim().split('\n'));
const fixture = fs.mkdtempSync(path.join(os.tmpdir(), 'amanah-package-form-'));
try {
    for (const file of [
        'app/Views/admin/package_form.php', 'app/Views/superadmin/package_form.php',
        'app/Views/layouts/sidebar.php', 'app/Views/layouts/navbar.php',
        'app/Views/shared/commission_simulation.php', 'public/assets/js/commission-simulation.js'
    ]) {
        if (!files.has(file)) continue;
        const destination = path.join(fixture, file);
        fs.mkdirSync(path.dirname(destination), { recursive: true });
        fs.writeFileSync(destination, git(['show', indexed ? `:${file}` : `${target}:${file}`]));
    }
    // Isolate rendering from sessions and DB; execute the actual form and partial PHP.
    fs.writeFileSync(path.join(fixture, 'render.php'), `<?php
set_error_handler(static function ($level, $message, $file, $line) {
    throw new ErrorException($message, 0, $level, $file, $line);
});
define('FCPATH', __DIR__ . '/public/');
function base_url($path) { return 'https://example.test/' . ltrim($path, '/'); }
function uri_string() { return ''; }
function esc($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function csrf_field() { return '<input type="hidden" name="csrf_test" value="fixture">'; }
function session($key) { return ['role' => $GLOBALS['sessionRole'], 'logged_in' => true, 'nama' => 'Test', 'email' => 'test@example.test'][$key] ?? null; }
class FormRenderer {
    public function extend($name) { return ''; }
    public function section($name) { return ''; }
    public function endSection() { return ''; }
    public function include($name) {
        ob_start();
        require __DIR__ . '/app/Views/' . $name . '.php';
        return ob_get_clean();
    }
    public function render($role, $edit) {
        $GLOBALS['sessionRole'] = $role === 'superadmin' ? 'super_admin' : 'admin';
        $dpRules = [];
        if ($edit) $package = ['id' => 25, 'nama' => '<script>alert(1)</script>', 'harga' => 28000000, 'komisi_mitra' => 2000000, 'komisi_manager' => 500000, 'komisi_cabang_pusat' => 500000, 'total_pool_komisi' => 3000000];
        ob_start();
        require __DIR__ . '/app/Views/' . $role . '/package_form.php';
        return ob_get_clean();
    }
}
foreach (['admin', 'superadmin'] as $role) {
    foreach ([false, true] as $edit) {
        $html = (new FormRenderer())->render($role, $edit);
        $action = $role . '/packages/' . ($edit ? '25/update' : 'store');
        if (strpos($html, base_url($action)) === false) throw new RuntimeException('Form action changed');
        if (strpos($html, 'name="csrf_test"') === false) throw new RuntimeException('CSRF field missing');
        if (strpos($html, 'data-simulation-mode="form"') === false) throw new RuntimeException('Simulation missing');
        if (strpos($html, 'commission-simulation.js?v=') === false) throw new RuntimeException('Script missing');
        if ($edit && strpos($html, '&lt;script&gt;alert(1)&lt;/script&gt;') === false) throw new RuntimeException('Package name not escaped');
        echo 'PASS: ' . $role . ($edit ? ' edit' : ' create') . PHP_EOL;
    }
}
`);
    const php = process.env.PHP_BINARY || (process.platform === 'win32' ? 'C:/xampp/php/php.exe' : 'php');
    const result = spawnSync(php, [path.join(fixture, 'render.php')], { encoding: 'utf8' });
    assert.ifError(result.error);
    assert.equal(result.status, 0, result.stderr || result.stdout);
    process.stdout.write(result.stdout);
    console.log(`PASS: forms and hashed script render from ${target} without working-tree dependencies`);
} finally {
    fs.rmSync(fixture, { recursive: true, force: true });
}
