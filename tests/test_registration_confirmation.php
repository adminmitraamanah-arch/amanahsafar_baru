<?php

// Local integration test: all fixture writes are rolled back, never committed.
define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . '/public/');
chdir(FCPATH);
require '../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);
$config = config('Database');
if (!in_array($config->default['hostname'], ['localhost', '127.0.0.1'], true)) {
    throw new RuntimeException('Only a local database is allowed.');
}
$db = \Config\Database::connect();
foreach (['users', 'audit_logs', 'landing_settings'] as $table) {
    $status = $db->query('SHOW TABLE STATUS WHERE Name = ?', [$db->prefixTable($table)])->getRowArray();
    if (($status['Engine'] ?? '') !== 'InnoDB') {
        throw new RuntimeException('Transactional table required: ' . $table);
    }
}
$checks = 0;
function verify($ok, string $label): void
{
    global $checks;
    if (!$ok) throw new RuntimeException($label);
    $checks++;
}
function runConfirmation(string $method = 'GET')
{
    $request = \Config\Services::incomingrequest(config('App'), false);
    $request->setMethod($method);
    \Config\Services::injectMock('request', $request);
    $request->setGlobal('post', ['user_id' => 1, 'agreement_accepted_at' => 'forged']);
    if ($method === 'POST') {
        $security = service('security');
        $request->setHeader(config('Security')->headerName, $security->getHash());
        (new \CodeIgniter\Filters\CSRF())->before($request);
    }
    $controller = new \App\Controllers\RegistrationConfirmation();
    $controller->initController($request, service('response'), service('logger'));
    return $controller->index();
}
$session = session();
$db->transBegin();
try {
    $suffix = bin2hex(random_bytes(5));
    $db->table('users')->insert([
        'nama_lengkap' => 'Uji Konfirmasi & Encoding', 'email' => 'wa-' . $suffix . '@example.test',
        'no_wa' => 'TEST-' . $suffix, 'domisili' => 'Fixture lokal', 'password_hash' => 'not-a-login-hash',
        'role' => 'mitra', 'status' => 'aktif', 'verification_status' => 'terverifikasi',
        'agreement_accepted_at' => '2026-10-18 10:00:00',
    ]);
    $id = $db->insertID();
    $before = $db->table('users')->where('id', $id)->get()->getRowArray();
    $session->remove(['logged_in', 'user_id']);
    verify(str_ends_with(runConfirmation()->getHeaderLine('Location'), '/login'), 'Login required');
    $session->set(['logged_in' => true, 'user_id' => $id]);
    \App\Models\LandingSettingModel::setVal('contact_whatsapp', '081234567890');
    $html = runConfirmation();
    verify(str_contains($html, 'Link konfirmasi WhatsApp belum diklik.'), 'Initial status');
    $unsafeRequest = \Config\Services::incomingrequest(config('App'), false);
    $unsafeRequest->setMethod('POST');
    $unsafeRequest->setGlobal('post', []);
    try {
        (new \CodeIgniter\Filters\CSRF())->before($unsafeRequest);
        throw new RuntimeException('CSRF request was not rejected');
    } catch (\CodeIgniter\Security\Exceptions\SecurityException $e) {
        verify(true, 'Missing CSRF rejected');
    }
    verify($db->table('audit_logs')->where('user_id', $id)->countAllResults() === 0, 'GET does not log a click');
    if (in_array('--render', $GLOBALS['argv'], true)) {
        file_put_contents(FCPATH . 'registration-confirmation-preview.html', $html);
    }
    $result = runConfirmation('POST');
    verify($result->getStatusCode() === 303, 'Redirect uses GET after POST');
    verify(str_starts_with($result->getHeaderLine('Location'), 'https://wa.me/6281234567890?'), 'Configured recipient');
    parse_str(parse_url($result->getHeaderLine('Location'), PHP_URL_QUERY), $query);
    verify(str_contains($query['text'], 'Nama: Uji Konfirmasi & Encoding'), 'Session identity used');
    $audit = $db->table('audit_logs')->where('user_id', $id)->get()->getRowArray();
    verify((int) $audit['entitas_id'] === (int) $id, 'Forged identity ignored');
    verify($audit['data_baru'] === null && $audit['ip_address'] === '', 'No unnecessary PII in audit');
    verify(str_contains(runConfirmation(), 'Link konfirmasi WhatsApp telah diklik.'), 'Recorded status');
    \App\Models\LandingSettingModel::setVal('contact_whatsapp', '+62 812 9876 5432');
    verify(str_starts_with(runConfirmation('POST')->getHeaderLine('Location'), 'https://wa.me/6281298765432?'), 'Changed recipient read on POST');
    verify($db->table('audit_logs')->where('user_id', $id)->countAllResults() === 2, 'Retry records only another click');
    \App\Models\LandingSettingModel::setVal('contact_whatsapp', 'invalid');
    verify(str_contains(runConfirmation('POST'), 'belum tersedia atau tidak valid'), 'Invalid recipient error');
    verify($db->table('audit_logs')->where('user_id', $id)->countAllResults() === 2, 'Invalid recipient does not log success');
    verify($db->table('users')->where('id', $id)->get()->getRowArray() === $before, 'PKS and account unchanged');
    $db->table('users')->where('id', $id)->update(['agreement_accepted_at' => null]);
    verify(str_ends_with(runConfirmation('POST')->getHeaderLine('Location'), '/agreement'), 'PKS required before click');
    $db->table('users')->where('id', $id)->update(['status' => 'nonaktif']);
    verify(str_ends_with(runConfirmation('POST')->getHeaderLine('Location'), '/login'), 'Inactive account rejected');
} finally {
    $db->transRollback();
    $session->remove(['logged_in', 'user_id']);
}
verify($db->table('users')->where('id', $id)->countAllResults() === 0, 'Fixtures rolled back');
echo "PASS: {$checks} integration checks; transaction rolled back.\n";
