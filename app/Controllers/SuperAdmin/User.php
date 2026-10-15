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
        
        $tab = $this->request->getGet('tab') ?? 'active';

        // Hitung total pengguna aktif dan sampah
        $activeCount = $db->table('users')->where('deleted_at', null)->countAllResults();
        $trashCount = $db->table('users')->where('deleted_at IS NOT NULL', null, false)->countAllResults();

        // Fetch users based on selected tab
        if ($tab === 'trash') {
            $users = $db->table('users u')
                ->select('u.*, p.nama_lengkap as nama_pembina')
                ->join('users p', 'p.id = u.pembina_id', 'left')
                ->where('u.deleted_at IS NOT NULL', null, false)
                ->orderBy('u.deleted_at', 'DESC')
                ->get()
                ->getResultArray();
        } else {
            $users = $db->table('users u')
                ->select('u.*, p.nama_lengkap as nama_pembina')
                ->join('users p', 'p.id = u.pembina_id', 'left')
                ->where('u.deleted_at', null)
                ->orderBy('u.id', 'ASC')
                ->get()
                ->getResultArray();
        }

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
            'title'           => 'Kelola Pengguna',
            'users'           => $users,
            'pembinas'        => $pembinas,
            'current_user_id' => session('user_id'),
            'active_tab'      => $tab,
            'active_count'    => $activeCount,
            'trash_count'     => $trashCount,
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

    public function delete($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan');
        }

        // Protection 1: Cannot delete own account
        if ((int)$id === (int)session('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Super Admin Anda sendiri');
        }

        // Protection 2: Cannot delete the last active Super Admin
        if ($user['role'] === 'super_admin') {
            $activeSuperAdmins = $this->userModel
                ->where('role', 'super_admin')
                ->where('status', 'aktif')
                ->countAllResults();
            if ($activeSuperAdmins <= 1) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus akun Super Admin terakhir di sistem');
            }
        }

        // Invalidate all active sessions across devices
        $this->revokeAllUserSessions($id);

        $db = \Config\Database::connect();

        // Reassign any users who had this user as pembina to null
        $db->table('users')->where('pembina_id', $id)->update(['pembina_id' => null]);

        // Soft delete the user & set status to nonaktif, anonymize email & no_wa to free unique constraints
        $nowTs = time();
        $anonymizedEmail = str_contains($user['email'], '.deleted.') ? $user['email'] : ($user['email'] . '.deleted.' . $nowTs);
        $anonymizedWa = (!empty($user['no_wa']) && !str_contains($user['no_wa'], '.deleted.')) ? ($user['no_wa'] . '.deleted.' . $nowTs) : $user['no_wa'];

        $this->userModel->update($id, [
            'email'      => $anonymizedEmail,
            'no_wa'      => $anonymizedWa,
            'status'     => 'nonaktif',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $this->userModel->delete($id);

        return redirect()->back()->with('success', "Akun pengguna {$user['nama_lengkap']} berhasil dipindahkan ke Tong Sampah");
    }

    public function bulkDelete()
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('/superadmin/users')->with('error', 'Metode request tidak diizinkan');
        }

        $ids = $this->request->getPost('ids');
        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada pengguna yang dipilih untuk dihapus');
        }

        $cleanIds = array_values(array_filter(array_map('intval', $ids), fn($id) => $id > 0));
        if (empty($cleanIds)) {
            return redirect()->back()->with('error', 'Daftar ID pengguna tidak valid');
        }

        $currentUserId = (int)session('user_id');
        // Proteksi 1: Tidak dapat menghapus akun diri sendiri yang sedang login
        $cleanIds = array_values(array_filter($cleanIds, fn($id) => $id !== $currentUserId));
        if (empty($cleanIds)) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun Anda sendiri');
        }

        // Proteksi 2: Tidak dapat menghapus seluruh Super Admin (wajib ada minimal 1 aktif)
        $activeSuperAdmins = $this->userModel
            ->where('role', 'super_admin')
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->findAll();

        $activeSuperAdminIds = array_map('intval', array_column($activeSuperAdmins, 'id'));
        $remainingSuperAdmins = array_diff($activeSuperAdminIds, $cleanIds);
        if (empty($remainingSuperAdmins)) {
            return redirect()->back()->with('error', 'Penghapusan dibatalkan: Sistem wajib menyisakan minimal satu akun Super Admin aktif');
        }

        $db = \Config\Database::connect();
        $deletedCount = 0;

        foreach ($cleanIds as $delId) {
            $user = $this->userModel->find($delId);
            if (!$user) {
                continue;
            }

            $this->revokeAllUserSessions($delId);
            $db->table('users')->where('pembina_id', $delId)->update(['pembina_id' => null]);
            $nowTs = time();
            $anonymizedEmail = str_contains($user['email'], '.deleted.') ? $user['email'] : ($user['email'] . '.deleted.' . $nowTs);
            $anonymizedWa = (!empty($user['no_wa']) && !str_contains($user['no_wa'], '.deleted.')) ? ($user['no_wa'] . '.deleted.' . $nowTs) : $user['no_wa'];

            $this->userModel->update($delId, [
                'email'      => $anonymizedEmail,
                'no_wa'      => $anonymizedWa,
                'status'     => 'nonaktif',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $this->userModel->delete($delId);
            $deletedCount++;
        }

        return redirect()->back()->with('success', "Berhasil memindahkan {$deletedCount} akun pengguna terpilih ke Tong Sampah");
    }

    public function restore($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $id)->where('deleted_at IS NOT NULL', null, false)->get()->getRowArray();

        if (!$user) {
            return redirect()->back()->with('error', 'Data pengguna di Tong Sampah tidak ditemukan');
        }

        // Recover original email and no_wa by stripping .deleted.<timestamp>
        $cleanEmail = preg_replace('/\.deleted\.\d+$/', '', $user['email']);
        $cleanWa = !empty($user['no_wa']) ? preg_replace('/\.deleted\.\d+$/', '', $user['no_wa']) : $user['no_wa'];

        // Protection: Check if original email or no_wa is currently in use by another ACTIVE user
        $emailConflict = $db->table('users')
            ->where('email', $cleanEmail)
            ->where('deleted_at', null)
            ->where('id !=', $id)
            ->countAllResults();

        if ($emailConflict > 0) {
            return redirect()->back()->with('error', "Gagal memulihkan: Email '{$cleanEmail}' saat ini sedang digunakan oleh akun aktif lain.");
        }

        if (!empty($cleanWa)) {
            $waConflict = $db->table('users')
                ->where('no_wa', $cleanWa)
                ->where('deleted_at', null)
                ->where('id !=', $id)
                ->countAllResults();

            if ($waConflict > 0) {
                return redirect()->back()->with('error', "Gagal memulihkan: Nomor WhatsApp '{$cleanWa}' saat ini sedang digunakan oleh akun aktif lain.");
            }
        }

        // Restore user to active state
        $db->table('users')->where('id', $id)->update([
            'email'      => $cleanEmail,
            'no_wa'      => $cleanWa,
            'status'     => 'aktif',
            'deleted_at' => null,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/superadmin/users?tab=trash')->with('success', "Akun pengguna {$user['nama_lengkap']} ({$cleanEmail}) berhasil dipulihkan!");
    }

    public function purge($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $id)->where('deleted_at IS NOT NULL', null, false)->get()->getRowArray();

        if (!$user) {
            return redirect()->back()->with('error', 'Data pengguna di Tong Sampah tidak ditemukan');
        }

        // Check if user has orders or commissions for financial audit preservation
        $orderCount = $db->table('orders')->where('user_id', $id)->countAllResults();
        $commCount = $db->table('commissions')->where('user_id', $id)->countAllResults();

        if ($orderCount > 0 || $commCount > 0) {
            return redirect()->back()->with('error', "Peringatan Keamanan: Akun ini memiliki riwayat {$orderCount} pesanan dan {$commCount} mutasi komisi. Untuk menjaga integritas audit keuangan, akun tidak dapat dihapus permanen.");
        }

        // Safe to purge
        $db->table('verifications')->where('user_id', $id)->delete();
        $db->table('users')->where('id', $id)->delete();

        return redirect()->to('/superadmin/users?tab=trash')->with('success', "Akun pengguna {$user['nama_lengkap']} berhasil dihapus permanen dari sistem.");
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
