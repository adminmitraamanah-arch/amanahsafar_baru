<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionModel extends Model
{
    protected $table = 'commissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'order_id',
        'user_id',
        'role_penerima',
        'jenis_komisi',
        'nominal_snapshot',
        'keterangan',
        'status',
        'paid_at',
        'cair_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Mesin Distribusi Komisi Berjenjang (Hierarchical Commission Engine)
     * Mengimplementasikan 4 skenario pembagian komisi sesuai aturan bisnis:
     * - Skenario 1: Cabang closing langsung -> Cabang menerima Full Pool
     * - Skenario 2: Mitra closing (dibina Cabang langsung) -> Mitra dapat Direct, Cabang dapat sisa Pool
     * - Skenario 3: Mitra binaan Manager terafiliasi Cabang -> Mitra, Manager, dan Cabang berbagi Pool
     * - Skenario 4: Mitra binaan Manager mandiri (Pusat) -> Jatah cabang lari ke Pusat
     * 
     * Dilengkapi Validasi Sum ketat (Total komisi terbagi harus selalu sama dengan Pool Paket).
     */
    public function generateCommissionsForOrder($orderId, $initialStatus = 'potensi')
    {
        $db = \Config\Database::connect();
        
        // 1. Cek apakah komisi untuk pesanan ini sudah pernah dibuat
        $existing = $this->where('order_id', (int)$orderId)->countAllResults();
        if ($existing > 0) {
            return false;
        }

        // 2. Ambil data pesanan dan item paket
        $order = $db->table('orders')->where('id', (int)$orderId)->get()->getRowArray();
        if (!$order) {
            return false;
        }

        $items = $db->table('order_items oi')
            ->select('oi.*, p.nama as paket_nama, p.harga, p.komisi_mitra, p.komisi_manager, p.komisi_cabang_pusat, p.total_pool_komisi')
            ->join('packages p', 'p.id = oi.package_id')
            ->where('oi.order_id', (int)$orderId)
            ->get()
            ->getResultArray();

        if (empty($items)) {
            return false;
        }

        $userModel = new UserModel();
        $buyer = $userModel->find($order['user_id']);
        if (!$buyer) {
            return false;
        }

        // Cari akun Pusat resmi untuk jatah limpahan pusat (skenario 4)
        $pusatUser = $db->table('users')->where('role', 'pusat')->where('status', 'aktif')->orderBy('id', 'ASC')->get()->getRowArray();
        if (!$pusatUser) {
            $pusatUser = $db->table('users')->where('role', 'super_admin')->orderBy('id', 'ASC')->get()->getRowArray();
        }
        $pusatId = $pusatUser ? (int)$pusatUser['id'] : 1;

        $commissionsToInsert = [];

        foreach ($items as $item) {
            $qty = max(1, (int)$item['qty']);
            $paketNama = $item['paket_nama'];

            // Ambil nominal komisi dinamis per paket (jika 0, gunakan standar industri 2jt / 500rb / 500rb)
            $rateMitra = (float)$item['komisi_mitra'] > 0 ? (float)$item['komisi_mitra'] : 2000000.00;
            $rateManager = (float)$item['komisi_manager'] > 0 ? (float)$item['komisi_manager'] : 500000.00;
            $rateCabang = (float)$item['komisi_cabang_pusat'] > 0 ? (float)$item['komisi_cabang_pusat'] : 500000.00;
            
            $poolPerPax = (float)$item['total_pool_komisi'] > 0 ? (float)$item['total_pool_komisi'] : ($rateMitra + $rateManager + $rateCabang);
            $totalPool = $poolPerPax * $qty;

            $itemKomisiMitra = $rateMitra * $qty;
            $itemKomisiManager = $rateManager * $qty;
            $itemKomisiCabang = $rateCabang * $qty;

            // Identifikasi aktor dan pohon pembina
            $buyerRole = $buyer['role'];

            if ($buyerRole === 'cabang') {
                // ==========================================
                // SKENARIO 1: Cabang closing langsung ke jamaah
                // Cabang berhak atas seluruh alokasi komisi (Full Pool)
                // ==========================================
                $commissionsToInsert[] = [
                    'order_id' => (int)$orderId,
                    'user_id' => (int)$buyer['id'],
                    'role_penerima' => 'cabang',
                    'jenis_komisi' => 'direct_cabang',
                    'nominal_snapshot' => $totalPool,
                    'keterangan' => "Penjualan Langsung Cabang - {$paketNama} ({$qty} Pax)",
                    'status' => $initialStatus,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            } else {
                // Pembeli adalah Mitra (atau Manager yang closing sendiri)
                // Mitra selalu mendapat jatah direct selling
                $commissionsToInsert[] = [
                    'order_id' => (int)$orderId,
                    'user_id' => (int)$buyer['id'],
                    'role_penerima' => 'mitra',
                    'jenis_komisi' => 'direct_mitra',
                    'nominal_snapshot' => $itemKomisiMitra,
                    'keterangan' => "Penjualan Pribadi Mitra - {$paketNama} ({$qty} Pax)",
                    'status' => $initialStatus,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                // Telusuri Pembina Tingkat 1 (Langsung)
                $pembina1 = null;
                if (!empty($buyer['pembina_id'])) {
                    $pembina1 = $userModel->find($buyer['pembina_id']);
                }

                if ($pembina1 && $pembina1['role'] === 'cabang') {
                    // ==========================================
                    // SKENARIO 2: Mitra dibina langsung oleh Cabang (tanpa Manager)
                    // Cabang mendapat jatah Cabang + jatah Manager
                    // ==========================================
                    $cabangTotal = $itemKomisiCabang + $itemKomisiManager;
                    $commissionsToInsert[] = [
                        'order_id' => (int)$orderId,
                        'user_id' => (int)$pembina1['id'],
                        'role_penerima' => 'cabang',
                        'jenis_komisi' => 'override_cabang',
                        'nominal_snapshot' => $cabangTotal,
                        'keterangan' => "Komisi Cabang dari Mitra Langsung: {$buyer['nama_lengkap']} - {$paketNama} ({$qty} Pax)",
                        'status' => $initialStatus,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                } elseif ($pembina1 && $pembina1['role'] === 'manager_mitra') {
                    // Pembina tingkat 1 adalah Manager Mitra -> Manager dapat jatah override
                    $commissionsToInsert[] = [
                        'order_id' => (int)$orderId,
                        'user_id' => (int)$pembina1['id'],
                        'role_penerima' => 'manager_mitra',
                        'jenis_komisi' => 'override_manager',
                        'nominal_snapshot' => $itemKomisiManager,
                        'keterangan' => "Komisi Pembinaan Manager dari Mitra: {$buyer['nama_lengkap']} - {$paketNama} ({$qty} Pax)",
                        'status' => $initialStatus,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];

                    // Telusuri Pembina Tingkat 2 (Induk dari Manager)
                    $pembina2 = null;
                    if (!empty($pembina1['pembina_id'])) {
                        $pembina2 = $userModel->find($pembina1['pembina_id']);
                    }

                    if ($pembina2 && $pembina2['role'] === 'cabang') {
                        // ==========================================
                        // SKENARIO 3: Manager terafiliasi ke Cabang
                        // Cabang mendapat jatah cabang
                        // ==========================================
                        $commissionsToInsert[] = [
                            'order_id' => (int)$orderId,
                            'user_id' => (int)$pembina2['id'],
                            'role_penerima' => 'cabang',
                            'jenis_komisi' => 'override_cabang',
                            'nominal_snapshot' => $itemKomisiCabang,
                            'keterangan' => "Komisi Wilayah Cabang dari Jaringan: {$pembina1['nama_lengkap']} / {$buyer['nama_lengkap']} - {$paketNama} ({$qty} Pax)",
                            'status' => $initialStatus,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                    } else {
                        // ==========================================
                        // SKENARIO 4: Manager mandiri (TIDAK terafiliasi Cabang)
                        // Jatah cabang LARI KE KANTOR PUSAT
                        // ==========================================
                        $commissionsToInsert[] = [
                            'order_id' => (int)$orderId,
                            'user_id' => (int)$pusatId,
                            'role_penerima' => 'pusat',
                            'jenis_komisi' => 'override_pusat',
                            'nominal_snapshot' => $itemKomisiCabang,
                            'keterangan' => "Limpahan Komisi Non-Cabang ke Kantor Pusat dari Manager: {$pembina1['nama_lengkap']} - {$paketNama} ({$qty} Pax)",
                            'status' => $initialStatus,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                    }
                } else {
                    // Fallback jika mitra langsung di bawah Pusat tanpa Manager/Cabang
                    $sisaPool = $totalPool - $itemKomisiMitra;
                    if ($sisaPool > 0) {
                        $commissionsToInsert[] = [
                            'order_id' => (int)$orderId,
                            'user_id' => (int)$pusatId,
                            'role_penerima' => 'pusat',
                            'jenis_komisi' => 'override_pusat',
                            'nominal_snapshot' => $sisaPool,
                            'keterangan' => "Limpahan Komisi ke Pusat dari Mitra Mandiri: {$buyer['nama_lengkap']} - {$paketNama} ({$qty} Pax)",
                            'status' => $initialStatus,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                    }
                }
            }

            // ==========================================
            // STRICT SUM VALIDATION DI LEVEL SISTEM
            // Total komisi terbagi harus selalu sama persis dengan total pool paket
            // ==========================================
            $currentSum = 0;
            foreach ($commissionsToInsert as $c) {
                if ($c['order_id'] === (int)$orderId) {
                    $currentSum += (float)$c['nominal_snapshot'];
                }
            }

            if (abs($currentSum - $totalPool) > 0.01) {
                log_message('error', "Validasi Sum Komisi Gagal pada Order {$orderId}! Total Sum: {$currentSum}, Expected Pool: {$totalPool}");
            }
        }

        // Simpan seluruh catatan komisi
        if (!empty($commissionsToInsert)) {
            $this->insertBatch($commissionsToInsert);
            return true;
        }

        return false;
    }

    /**
     * Memperbarui status komisi untuk suatu pesanan.
     * Dipanggil saat status pesanan berubah:
     * - Saat DP diterima: status komisi = 'potensi'
     * - Saat Pelunasan diverifikasi: status komisi = 'siap_cair' (dan set cair_at)
     * - Saat pesanan dibatalkan: status komisi = 'dibatalkan'
     */
    public function updateCommissionStatusForOrder($orderId, $newStatus)
    {
        $data = ['status' => $newStatus];
        if ($newStatus === 'siap_cair') {
            $data['cair_at'] = date('Y-m-d H:i:s');
        }

        return $this->where('order_id', (int)$orderId)->set($data)->update();
    }

    /**
     * Mengambil saldo Potensi Komisi (menunggu pelunasan)
     */
    public function getPotensiKomisiByUser($userId)
    {
        $res = $this->where('user_id', (int)$userId)
            ->where('status', 'potensi')
            ->selectSum('nominal_snapshot')
            ->first();

        return (float)($res['nominal_snapshot'] ?? 0);
    }

    /**
     * Mengambil Saldo Komisi Siap Cair (jamaah sudah lunas & belum ditarik)
     */
    public function getSaldoSiapCairByUser($userId)
    {
        $res = $this->where('user_id', (int)$userId)
            ->where('status', 'siap_cair')
            ->selectSum('nominal_snapshot')
            ->first();

        $totalCair = (float)($res['nominal_snapshot'] ?? 0);

        // Kurangi dengan penarikan yang sedang diproses (menunggu_transfer)
        $db = \Config\Database::connect();
        $pendingWd = $db->table('withdrawals')
            ->where('user_id', (int)$userId)
            ->where('status', 'menunggu_transfer')
            ->selectSum('nominal')
            ->get()
            ->getRowArray();

        $totalPendingWd = (float)($pendingWd['nominal'] ?? 0);

        return max(0, $totalCair - $totalPendingWd);
    }

    /**
     * Buku Mutasi Komisi Lengkap Mirip Mutasi Rekening Bank
     */
    public function getMutasiLedgerByUser($userId, $statusFilter = 'all')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('commissions c')
            ->select('c.*, o.no_order, o.total as order_total, o.created_at as tgl_pesan, u.nama_lengkap as nama_pembeli')
            ->join('orders o', 'o.id = c.order_id', 'left')
            ->join('users u', 'u.id = o.user_id', 'left')
            ->where('c.user_id', (int)$userId);

        if ($statusFilter !== 'all') {
            $builder->where('c.status', $statusFilter);
        }

        return $builder->orderBy('c.id', 'DESC')->get()->getResultArray();
    }
}
