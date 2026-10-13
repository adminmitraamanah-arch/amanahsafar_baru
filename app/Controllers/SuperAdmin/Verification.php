<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\VerificationModel;
use App\Models\UserModel;

class Verification extends BaseController
{
    protected $verificationModel;
    protected $userModel;

    public function __construct()
    {
        $this->verificationModel = new VerificationModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('verifications v')
            ->select('v.*, u.nama_lengkap, u.email, u.no_wa, u.domisili, u.role as user_role, u.agreement_accepted_at, vc.kode as kode_mitra')
            ->join('users u', 'u.id = v.user_id', 'left')
            ->join('vouchers vc', 'vc.used_by = u.id', 'left')
            ->where('u.id IS NOT NULL')
            ->orderBy('v.created_at', 'DESC');

        $verifications = $builder->get()->getResultArray();

        $data = [
            'title' => 'Verifikasi Pengguna',
            'verifications' => $verifications,
        ];

        return view('superadmin/verifications', $data);
    }

    public function show($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $verification = $db->table('verifications v')
            ->select('v.*, r.nama_lengkap as reviewer_nama, r.email as reviewer_email')
            ->join('users r', 'r.id = v.reviewed_by', 'left')
            ->where('v.id', $id)
            ->get()
            ->getRowArray();

        if (!$verification) {
            return redirect()->to('/superadmin/verifications')->with('error', 'Data verifikasi tidak ditemukan.');
        }

        $user = $db->table('users')->where('id', $verification['user_id'])->get()->getRowArray();
        if (!$user) {
            return redirect()->to('/superadmin/verifications')->with('error', 'Data pengguna terkait tidak ditemukan.');
        }

        // Cari data pembina jika ada
        $pembina = null;
        if (!empty($user['pembina_id'])) {
            $pembina = $db->table('users')->where('id', $user['pembina_id'])->get()->getRowArray();
        }

        // Cari voucher yang digunakan pendaftar (kode mitra)
        $voucher = $db->table('vouchers')->where('used_by', $user['id'])->get()->getRowArray();

        // Cek file KTP
        $ktpExists = false;
        $ktpUrl = null;
        if (!empty($verification['ktp_path'])) {
            $ktpPath = $this->resolveFilePath($verification['ktp_path']);
            if ($ktpPath && file_exists($ktpPath)) {
                $ktpExists = true;
                $ktpUrl = base_url('superadmin/verifications/document/' . $verification['id'] . '/ktp');
            }
        }

        // Cek file Swafoto jika ada
        $swafotoExists = false;
        $swafotoUrl = null;
        if (!empty($verification['swafoto_path'])) {
            $swafotoPath = $this->resolveFilePath($verification['swafoto_path']);
            if ($swafotoPath && file_exists($swafotoPath)) {
                $swafotoExists = true;
                $swafotoUrl = base_url('superadmin/verifications/document/' . $verification['id'] . '/swafoto');
            }
        }

        // Data PKS
        $pksNumber = 'PKS/AS/' . date('Ym', strtotime($user['agreement_accepted_at'] ?? $verification['created_at'])) . '/' . str_pad((string)$user['id'], 4, '0', STR_PAD_LEFT);
        $pksAccepted = !empty($user['agreement_accepted_at']);

        $data = [
            'title' => 'Detail Verifikasi: ' . ($user['nama_lengkap'] ?? ('User #' . $user['id'])),
            'verification' => $verification,
            'user' => $user,
            'pembina' => $pembina,
            'voucher' => $voucher,
            'ktpExists' => $ktpExists,
            'ktpUrl' => $ktpUrl,
            'swafotoExists' => $swafotoExists,
            'swafotoUrl' => $swafotoUrl,
            'pksNumber' => $pksNumber,
            'pksAccepted' => $pksAccepted,
        ];

        return view('superadmin/verification_detail', $data);
    }

    public function document($id, $type)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return $this->response->setStatusCode(403, 'Akses ditolak');
        }

        if (!in_array($type, ['ktp', 'swafoto'], true)) {
            return $this->response->setStatusCode(404, 'Jenis dokumen tidak valid');
        }

        $verification = $this->verificationModel->find($id);
        if (!$verification) {
            return $this->response->setStatusCode(404, 'Verifikasi tidak ditemukan');
        }

        $relativePath = $type === 'ktp' ? $verification['ktp_path'] : $verification['swafoto_path'];
        if (empty($relativePath)) {
            return $this->response->setStatusCode(404, 'Dokumen belum diunggah');
        }

        $filePath = $this->resolveFilePath($relativePath);
        if (!$filePath || !file_exists($filePath)) {
            return $this->response->setStatusCode(404, 'Berkas fisik dokumen tidak ditemukan');
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        $filename = basename($filePath);

        $download = $this->request->getGet('download');
        $disposition = $download === '1' ? 'attachment' : 'inline';

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', $disposition . '; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'private, max-age=3600')
            ->setBody(file_get_contents($filePath));
    }

    public function pks($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $verification = $db->table('verifications v')->where('v.id', $id)->get()->getRowArray();
        if (!$verification) {
            return redirect()->to('/superadmin/verifications')->with('error', 'Data verifikasi tidak ditemukan.');
        }

        $user = $db->table('users')->where('id', $verification['user_id'])->get()->getRowArray();
        if (!$user) {
            return redirect()->to('/superadmin/verifications')->with('error', 'Data pengguna tidak ditemukan.');
        }

        $voucher = $db->table('vouchers')->where('used_by', $user['id'])->get()->getRowArray();
        $pembina = !empty($user['pembina_id']) ? $db->table('users')->where('id', $user['pembina_id'])->get()->getRowArray() : null;

        $pksNumber = 'PKS/AS/' . date('Ym', strtotime($user['agreement_accepted_at'] ?? $verification['created_at'])) . '/' . str_pad((string)$user['id'], 4, '0', STR_PAD_LEFT);

        return view('superadmin/verification_pks_print', [
            'verification' => $verification,
            'user' => $user,
            'voucher' => $voucher,
            'pembina' => $pembina,
            'pksNumber' => $pksNumber,
        ]);
    }

    public function approve($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $verification = $this->verificationModel->find($id);
        if (!$verification) {
            return redirect()->to('/superadmin/verifications')->with('error', 'Data verifikasi tidak ditemukan.');
        }

        $notes = trim((string)$this->request->getPost('catatan'));

        $db = \Config\Database::connect();
        $db->transStart();

        $this->verificationModel->update($id, [
            'status' => 'terverifikasi',
            'reviewed_by' => session('user_id'),
            'catatan' => $notes ?: 'Pengajuan verifikasi disetujui oleh Super Admin.',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $db->table('users')->where('id', $verification['user_id'])->update([
            'verification_status' => 'terverifikasi',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses persetujuan verifikasi.');
        }

        return redirect()->to('/superadmin/verifications/' . $id)->with('success', 'Verifikasi berhasil disetujui. Akun mitra kini berstatus Terverifikasi.');
    }

    public function reject($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $verification = $this->verificationModel->find($id);
        if (!$verification) {
            return redirect()->to('/superadmin/verifications')->with('error', 'Data verifikasi tidak ditemukan.');
        }

        $catatan = trim((string)$this->request->getPost('catatan'));
        if (empty($catatan)) {
            return redirect()->back()->with('error', 'Alasan penolakan wajib diisi agar pengguna dapat memperbaiki data.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->verificationModel->update($id, [
            'status' => 'ditolak',
            'reviewed_by' => session('user_id'),
            'catatan' => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $db->table('users')->where('id', $verification['user_id'])->update([
            'verification_status' => 'ditolak',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses penolakan verifikasi.');
        }

        return redirect()->to('/superadmin/verifications/' . $id)->with('success', 'Pengajuan verifikasi telah ditolak.');
    }

    private function resolveFilePath($relativePath)
    {
        $baseName = basename($relativePath);

        $candidates = [
            WRITEPATH . 'uploads/' . $relativePath,
            WRITEPATH . 'uploads/uploads/verifikasi/' . $baseName,
            WRITEPATH . 'uploads/verifikasi/' . $baseName,
            WRITEPATH . $relativePath,
            FCPATH . 'uploads/' . $relativePath,
            FCPATH . 'uploads/verifikasi/' . $baseName
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate) && is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
