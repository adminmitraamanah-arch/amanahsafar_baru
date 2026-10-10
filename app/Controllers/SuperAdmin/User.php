<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        
        // Fetch all users with their pembina name
        $users = $db->table('users u')
            ->select('u.*, p.nama_lengkap as nama_pembina')
            ->join('users p', 'p.id = u.pembina_id', 'left')
            ->where('u.deleted_at', null)
            ->orderBy('u.id', 'ASC')
            ->get()
            ->getResultArray();

        // Fetch potential pembinas (Pusat, Cabang, Manager Mitra, Admin, Super Admin)
        $pembinas = $db->table('users')
            ->select('id, nama_lengkap, role')
            ->whereIn('role', ['super_admin', 'admin', 'pusat', 'cabang', 'manager_mitra'])
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Kelola Pengguna',
            'users' => $users,
            'pembinas' => $pembinas,
            'current_user_id' => session('user_id'),
        ];

        return view('superadmin/users', $data);
    }

    public function update($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan');
        }

        $namaLengkap = trim($this->request->getPost('nama_lengkap') ?? '');
        $email = trim(strtolower($this->request->getPost('email') ?? ''));
        $noWa = trim($this->request->getPost('no_wa') ?? '');
        $domisili = trim($this->request->getPost('domisili') ?? '');
        $role = trim($this->request->getPost('role') ?? '');
        $verificationStatus = trim($this->request->getPost('verification_status') ?? '');
        $pembinaId = $this->request->getPost('pembina_id');
        $pembinaId = (!empty($pembinaId) && is_numeric($pembinaId)) ? (int)$pembinaId : null;

        // Basic validation
        if (empty($namaLengkap) || strlen($namaLengkap) < 3) {
            return redirect()->back()->with('error', 'Nama lengkap minimal 3 karakter')->withInput();
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Format email tidak valid')->withInput();
        }

        if (empty($noWa)) {
            return redirect()->back()->with('error', 'Nomor WhatsApp wajib diisi')->withInput();
        }

        $validRoles = ['super_admin', 'admin', 'pusat', 'cabang', 'manager_mitra', 'mitra'];
        if (!in_array($role, $validRoles, true)) {
            return redirect()->back()->with('error', 'Role pengguna tidak valid')->withInput();
        }

        $validVerif = ['belum_verifikasi', 'menunggu_review', 'terverifikasi', 'ditolak'];
        if (!in_array($verificationStatus, $validVerif, true)) {
            return redirect()->back()->with('error', 'Status verifikasi tidak valid')->withInput();
        }

        // Email uniqueness check (exclude current user)
        $db = \Config\Database::connect();
        $emailExists = $db->table('users')
            ->where('email', $email)
            ->where('id !=', $id)
            ->countAllResults();
        if ($emailExists > 0) {
            return redirect()->back()->with('error', 'Email sudah digunakan oleh pengguna lain')->withInput();
        }

        // WhatsApp uniqueness check (exclude current user)
        $waExists = $db->table('users')
            ->where('no_wa', $noWa)
            ->where('id !=', $id)
            ->countAllResults();
        if ($waExists > 0) {
            return redirect()->back()->with('error', 'Nomor WhatsApp sudah digunakan oleh pengguna lain')->withInput();
        }

        // Protect Super Admin role if it's own account or last Super Admin
        if ((int)$id === (int)session('user_id') && $role !== 'super_admin') {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role akun Super Admin Anda sendiri')->withInput();
        }

        if ($user['role'] === 'super_admin' && $role !== 'super_admin') {
            $activeSuperAdmins = $this->userModel
                ->where('role', 'super_admin')
                ->where('status', 'aktif')
                ->countAllResults();
            if ($activeSuperAdmins <= 1) {
                return redirect()->back()->with('error', 'Tidak dapat mengubah role Super Admin terakhir di sistem')->withInput();
            }
        }

        // Prevent circular pembina (cannot be own pembina)
        if ($pembinaId === (int)$id) {
            $pembinaId = null;
        }

        // Update data while preserving relations, history, and password
        $updateData = [
            'nama_lengkap' => $namaLengkap,
            'email' => $email,
            'no_wa' => $noWa,
            'domisili' => $domisili,
            'role' => $role,
            'verification_status' => $verificationStatus,
            'pembina_id' => $pembinaId,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->userModel->update($id, $updateData);

        // Sync verifications table record
        $verifRecord = $db->table('verifications')->where('user_id', $id)->get()->getRowArray();
        if ($verifRecord) {
            $db->table('verifications')->where('id', $verifRecord['id'])->update([
                'status' => $verificationStatus,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $db->table('verifications')->insert([
                'user_id' => $id,
                'status' => $verificationStatus,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->back()->with('success', 'Perubahan data tersimpan');
    }

    public function deactivate($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan');
        }

        // Protection 1: Cannot deactivate own account
        if ((int)$id === (int)session('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Super Admin Anda sendiri');
        }

        // Protection 2: Cannot deactivate the last active Super Admin
        if ($user['role'] === 'super_admin') {
            $activeSuperAdmins = $this->userModel
                ->where('role', 'super_admin')
                ->where('status', 'aktif')
                ->countAllResults();
            if ($activeSuperAdmins <= 1) {
                return redirect()->back()->with('error', 'Tidak dapat menonaktifkan akun Super Admin terakhir di sistem');
            }
        }

        // Update status to nonaktif in database (retains all data, transactions, commissions, hierarchy)
        // ActiveUserFilter will automatically intercept any active session on next request, revoke access, and alert the user.
        $this->userModel->update($id, [
            'status' => 'nonaktif',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('deactivated', "Akun {$user['nama_lengkap']} dinonaktifkan");
    }

    public function activate($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan');
        }

        // Update status to aktif
        $this->userModel->update($id, [
            'status' => 'aktif',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', "Akun {$user['nama_lengkap']} diaktifkan kembali");
    }

    /**
     * Invalidate all active file-based sessions belonging to a specific user across all devices/browsers
     */
    protected function revokeAllUserSessions($userId): void
    {
        $sessionPath = WRITEPATH . 'session';
        if (!is_dir($sessionPath)) {
            return;
        }

        $files = glob($sessionPath . '/ci_session*');
        if (!$files) {
            return;
        }

        $searchString1 = 'user_id|s:' . strlen((string)$userId) . ':"' . $userId . '"';
        $searchString2 = 'user_id|i:' . $userId . ';';

        foreach ($files as $file) {
            $content = @file_get_contents($file);
            if ($content && (str_contains($content, $searchString1) || str_contains($content, $searchString2))) {
                @unlink($file);
            }
        }
    }
}
