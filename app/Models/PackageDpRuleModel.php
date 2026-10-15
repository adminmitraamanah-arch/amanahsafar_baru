<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageDpRuleModel extends Model
{
    protected $table = 'package_dp_rules';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'package_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'nominal_dp',
        'keterangan'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Mengambil aturan DP aktif untuk paket tertentu berdasarkan tanggal order.
     */
    public function getActiveRuleForDate($packageId, $date = null)
    {
        $targetDate = $date ? date('Y-m-d', strtotime($date)) : date('Y-m-d');

        return $this->where('package_id', (int)$packageId)
            ->where('tanggal_mulai <=', $targetDate)
            ->where('tanggal_selesai >=', $targetDate)
            ->orderBy('id', 'DESC')
            ->first();
    }

    /**
     * Mengambil seluruh daftar aturan DP untuk satu paket (untuk form edit paket admin).
     */
    public function getRulesByPackage($packageId)
    {
        return $this->where('package_id', (int)$packageId)
            ->orderBy('tanggal_mulai', 'ASC')
            ->findAll();
    }

    /**
     * Menghitung batas minimal DP yang wajib dibayarkan saat pemesanan paket.
     * Menggunakan Model A: mencari aturan tanggal kalender aktif.
     * Jika tidak ada aturan rentang tanggal aktif, gunakan nominal_dp_default dari tabel packages.
     */
    public function calculateMinimalDp($packageId, $qty = 1, $date = null)
    {
        $qty = max(1, (int)$qty);
        $activeRule = $this->getActiveRuleForDate($packageId, $date);

        if ($activeRule && (float)$activeRule['nominal_dp'] > 0) {
            $perPax = (float)$activeRule['nominal_dp'];
            return [
                'has_rule' => true,
                'per_pax' => $perPax,
                'total_min_dp' => $perPax * $qty,
                'keterangan' => $activeRule['keterangan'] ?: ('Periode ' . date('d M', strtotime($activeRule['tanggal_mulai'])) . ' - ' . date('d M Y', strtotime($activeRule['tanggal_selesai']))),
            ];
        }

        // Fallback: periksa minimal_dp_default di paket
        $db = \Config\Database::connect();
        $pkg = $db->table('packages')->select('minimal_dp_default, harga')->where('id', (int)$packageId)->get()->getRowArray();
        $defaultPerPax = (float)($pkg['minimal_dp_default'] ?? 0);

        if ($defaultPerPax > 0) {
            return [
                'has_rule' => false,
                'per_pax' => $defaultPerPax,
                'total_min_dp' => $defaultPerPax * $qty,
                'keterangan' => 'Tarif DP Reguler',
            ];
        }

        // Jika tidak disetel, default ke 20% dari harga paket
        $hargaPerPax = (float)($pkg['harga'] ?? 0);
        $estimasiDp = $hargaPerPax > 0 ? ($hargaPerPax * 0.2) : 0;

        return [
            'has_rule' => false,
            'per_pax' => $estimasiDp,
            'total_min_dp' => $estimasiDp * $qty,
            'keterangan' => 'Estimasi Uang Muka',
        ];
    }
}
