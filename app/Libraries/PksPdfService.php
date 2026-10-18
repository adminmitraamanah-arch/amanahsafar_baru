<?php

namespace App\Libraries;

use App\Models\UserModel;
use App\Models\VerificationModel;
use App\Models\VoucherModel;

class PksPdfService
{
    protected UserModel $userModel;
    protected VerificationModel $verificationModel;
    protected VoucherModel $voucherModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->verificationModel = new VerificationModel();
        $this->voucherModel = new VoucherModel();
    }

    /**
     * Mendapatkan path absolut berkas PDF PKS pengguna
     */
    public function getPdfPath(int $userId): string
    {
        $dir = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'pks';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir . DIRECTORY_SEPARATOR . 'pks_user_' . $userId . '.pdf';
    }

    /**
     * Memeriksa apakah berkas PDF PKS pengguna sudah tersedia secara fisik
     */
    public function hasPdf(int $userId): bool
    {
        $path = $this->getPdfPath($userId);
        if (!is_file($path) || filesize($path) < 1000) {
            return false;
        }

        // Jika template cetak lebih baru dari berkas PDF, otomatis trigger generate ulang
        $templatePath = APPPATH . 'Views/superadmin/verification_pks_print.php';
        if (is_file($templatePath) && filemtime($templatePath) > filemtime($path)) {
            return false;
        }

        return true;
    }

    /**
     * Menghasilkan nomor resmi PKS secara deterministik
     */
    public function getPksNumber(array $user): string
    {
        $dateStr = $user['agreement_accepted_at'] ?? date('Y-m-d H:i:s');
        return 'PKS/AS/' . date('Ym', strtotime($dateStr)) . '/' . str_pad((string)$user['id'], 4, '0', STR_PAD_LEFT);
    }

    /**
     * Mencari executable browser headless yang tersedia di sistem
     */
    protected function findBrowserBinary(): ?string
    {
        $candidates = [
            'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
            'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
            'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
            'msedge.exe',
            'chrome.exe',
            '/usr/bin/google-chrome',
            '/usr/bin/chromium-browser',
            '/usr/bin/chromium',
        ];

        foreach ($candidates as $bin) {
            if (is_file($bin) && is_readable($bin)) {
                return $bin;
            }
        }

        // Cek jika tersedia di PATH sistem
        $whichCmd = DIRECTORY_SEPARATOR === '\\' ? 'where' : 'which';
        foreach (['msedge.exe', 'chrome.exe', 'google-chrome', 'chromium'] as $cmdName) {
            $check = @shell_exec("$whichCmd " . escapeshellarg($cmdName) . " 2>&1");
            if ($check) {
                $lines = array_filter(array_map('trim', explode("\n", $check)));
                foreach ($lines as $line) {
                    if (is_file($line)) {
                        return $line;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Menghasilkan dokumen PDF PKS untuk pengguna
     *
     * @param int $userId ID pengguna yang telah menyetujui PKS atau sedang di tahap persetujuan
     * @param bool $force Jika true, generate ulang meskipun berkas sudah ada
     * @param bool $allowDraft Jika true, mengizinkan generate naskah pratinjau sebelum agreement_accepted_at terisi
     * @return string|null Path berkas PDF yang dihasilkan, atau null jika gagal
     */
    public function generate(int $userId, bool $force = false, bool $allowDraft = false): ?string
    {
        $user = $this->userModel->find($userId);
        if (!$user) {
            return null;
        }

        if (empty($user['agreement_accepted_at']) && !$allowDraft) {
            return null;
        }

        $targetPdf = $this->getPdfPath($userId);
        if (!$force && $this->hasPdf($userId)) {
            return $targetPdf;
        }

        $verification = $this->verificationModel->where('user_id', $userId)->first() ?? [];
        $voucher = $this->voucherModel->where('used_by', $userId)->first() ?? null;
        $pembina = !empty($user['pembina_id']) ? $this->userModel->find($user['pembina_id']) : null;
        $pksNumber = $this->getPksNumber($user);

        // Render HTML PKS resmi
        $html = view('superadmin/verification_pks_print', [
            'user'         => $user,
            'verification' => $verification,
            'voucher'      => $voucher,
            'pembina'      => $pembina,
            'pksNumber'    => $pksNumber,
            'hideToolbar'  => true,
            'isPdf'        => true,
        ]);

        $browserBinary = $this->findBrowserBinary();
        if (!$browserBinary) {
            log_message('error', 'PksPdfService: Tidak ditemukan binary browser headless untuk generate PDF.');
            return null;
        }

        $tempDir = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'pks';
        $tempHtml = $tempDir . DIRECTORY_SEPARATOR . 'temp_' . $userId . '_' . bin2hex(random_bytes(6)) . '.html';

        try {
            file_put_contents($tempHtml, $html);

            $cmd = escapeshellarg($browserBinary)
                . ' --headless --disable-gpu --run-all-compositor-stages-before-draw --no-pdf-header-footer '
                . escapeshellarg('--print-to-pdf=' . $targetPdf) . ' '
                . escapeshellarg($tempHtml);

            exec($cmd, $output, $returnCode);

            if (file_exists($tempHtml)) {
                @unlink($tempHtml);
            }

            if ($returnCode === 0 && is_file($targetPdf) && filesize($targetPdf) > 1000) {
                return $targetPdf;
            }

            log_message('error', 'PksPdfService: Gagal mencetak PDF. Return code: ' . $returnCode);
            return null;
        } catch (\Throwable $e) {
            if (file_exists($tempHtml)) {
                @unlink($tempHtml);
            }
            log_message('error', 'PksPdfService Exception: ' . $e->getMessage());
            return null;
        }
    }
}
