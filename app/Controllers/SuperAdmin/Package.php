<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\PackageModel;

class Package extends BaseController
{
    protected $packageModel;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
    }

    public function index()
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        $packages = $this->packageModel->findAll();
        if (class_exists(\App\Libraries\CatalogCommission::class)) {
            foreach ($packages as &$package) {
                $package['commission_distribution'] = \App\Libraries\CatalogCommission::distributionForRole($package, (string)session('role'));
            }
            unset($package);
        }
        $data = [
            'title' => 'Katalog Paket',
            'packages' => $packages,
        ];

        return view('superadmin/packages', $data);
    }

    public function create()
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        return view('superadmin/package_form', [
            'title' => 'Buat Paket',
            'dpRules' => [],
        ]);
    }

    public function store()
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $gambarName = null;
        $fileGambar = $this->request->getFile('gambar');
        $hasNewUpload = $fileGambar && $fileGambar->getError() !== UPLOAD_ERR_NO_FILE;

        if ($hasNewUpload) {
            $validationRules = [
                'gambar' => [
                    'label' => 'Gambar Paket',
                    'rules' => [
                        'uploaded[gambar]',
                        'is_image[gambar]',
                        'mime_in[gambar,image/jpg,image/jpeg,image/png,image/webp]',
                        'ext_in[gambar,jpg,jpeg,png,webp]',
                        'max_size[gambar,2048]',
                    ],
                    'errors' => [
                        'is_image' => 'File yang diunggah harus berupa gambar yang valid.',
                        'mime_in'  => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
                        'ext_in'   => 'Ekstensi file harus .jpg, .jpeg, .png, atau .webp.',
                        'max_size' => 'Ukuran gambar maksimal adalah 2MB.',
                    ],
                ],
            ];

            if (!$this->validate($validationRules)) {
                return redirect()->back()->withInput()->with('error', $this->validator->getError('gambar'));
            }

            if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
                $gambarName = $fileGambar->getRandomName();
                try {
                    $fileGambar->move(FCPATH . 'uploads/packages', $gambarName);
                } catch (\Throwable $e) {
                    return redirect()->back()->withInput()->with('error', 'Gagal memindahkan file gambar paket ke direktori penyimpanan.');
                }
            } else {
                return redirect()->back()->withInput()->with('error', 'File gambar tidak valid atau gagal diunggah.');
            }
        }

        // Process includes array
        $rawIncludes = $this->request->getPost('includes');
        $includes = [];
        if (is_array($rawIncludes)) {
            foreach ($rawIncludes as $item) {
                $item = trim((string)$item);
                if ($item !== '') {
                    $includes[] = $item;
                }
            }
        }

        // Process excludes array
        $rawExcludes = $this->request->getPost('excludes');
        $excludes = [];
        if (is_array($rawExcludes)) {
            foreach ($rawExcludes as $item) {
                $item = trim((string)$item);
                if ($item !== '') {
                    $excludes[] = $item;
                }
            }
        }

        $hargaQuad = $this->request->getPost('harga_quad');
        $hargaTriple = $this->request->getPost('harga_triple');
        $hargaDouble = $this->request->getPost('harga_double');
        $harga = $this->request->getPost('harga');
        if (empty($harga) && !empty($hargaQuad)) {
            $harga = $hargaQuad;
        }

        // Komisi Hierarki & Default DP
        $komisiMitra = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('komisi_mitra'));
        $komisiManager = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('komisi_manager'));
        $komisiCabangPusat = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('komisi_cabang_pusat'));
        $totalPool = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('total_pool_komisi'));
        if ($totalPool <= 0) {
            $totalPool = $komisiMitra + $komisiManager + $komisiCabangPusat;
        }
        $minDpDefault = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('minimal_dp_default'));

        $packageId = $this->packageModel->insert([
            'nama' => $this->request->getPost('nama'),
            'tanggal_keberangkatan' => $this->request->getPost('tanggal_keberangkatan') ?: null,
            'program_durasi' => $this->request->getPost('program_durasi'),
            'maskapai' => $this->request->getPost('maskapai'),
            'hotel_mekkah' => $this->request->getPost('hotel_mekkah'),
            'hotel_madinah' => $this->request->getPost('hotel_madinah'),
            'kategori' => $this->request->getPost('kategori'),
            'harga' => $harga ?: 0,
            'harga_quad' => !empty($hargaQuad) ? $hargaQuad : null,
            'harga_triple' => !empty($hargaTriple) ? $hargaTriple : null,
            'harga_double' => !empty($hargaDouble) ? $hargaDouble : null,
            'komisi_mitra' => $komisiMitra,
            'komisi_manager' => $komisiManager,
            'komisi_cabang_pusat' => $komisiCabangPusat,
            'total_pool_komisi' => $totalPool,
            'minimal_dp_default' => $minDpDefault,
            'seat_tersedia' => (int)($this->request->getPost('seat_tersedia') ?? 0),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'includes' => !empty($includes) ? json_encode(array_values($includes), JSON_UNESCAPED_UNICODE) : null,
            'excludes' => !empty($excludes) ? json_encode(array_values($excludes), JSON_UNESCAPED_UNICODE) : null,
            'gambar' => $gambarName,
            'status' => $this->request->getPost('status') ?: 'aktif',
            'created_by' => session('user_id')
        ]);

        if (!$packageId) {
            if ($gambarName && file_exists(FCPATH . 'uploads/packages/' . $gambarName)) {
                @unlink(FCPATH . 'uploads/packages/' . $gambarName);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data paket ke database.');
        }

        // Simpan Model A Aturan DP Rentang Tanggal
        $dpRuleModel = new \App\Models\PackageDpRuleModel();
        $dpKeterangans = $this->request->getPost('dp_rule_keterangan');
        $dpTglMulai = $this->request->getPost('dp_rule_tgl_mulai');
        $dpTglSelesai = $this->request->getPost('dp_rule_tgl_selesai');
        $dpNominals = $this->request->getPost('dp_rule_nominal');

        if (is_array($dpNominals) && $packageId) {
            for ($i = 0; $i < count($dpNominals); $i++) {
                $nom = (float)str_replace(['.', ','], ['', '.'], (string)($dpNominals[$i] ?? 0));
                $tMulai = $dpTglMulai[$i] ?? null;
                $tSelesai = $dpTglSelesai[$i] ?? null;
                $ket = trim((string)($dpKeterangans[$i] ?? ''));
                if ($nom > 0 && !empty($tMulai) && !empty($tSelesai)) {
                    $dpRuleModel->insert([
                        'package_id' => (int)$packageId,
                        'tanggal_mulai' => $tMulai,
                        'tanggal_selesai' => $tSelesai,
                        'nominal_dp' => $nom,
                        'keterangan' => $ket,
                    ]);
                }
            }
        }

        return redirect()->to('/superadmin/packages')->with('success', 'Data paket berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        $package = $this->packageModel->find($id);
        if (!$package) {
            return redirect()->to('/superadmin/packages')->with('error', 'Paket tidak ditemukan');
        }

        $dpRuleModel = new \App\Models\PackageDpRuleModel();
        $dpRules = $dpRuleModel->getRulesByPackage($id);

        return view('superadmin/package_form', [
            'title' => 'Edit Paket',
            'package' => $package,
            'dpRules' => $dpRules,
        ]);
    }

    public function update($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $package = $this->packageModel->find($id);
        if (!$package) {
            return redirect()->to('/superadmin/packages')->with('error', 'Paket tidak ditemukan');
        }

        // Process includes array
        $rawIncludes = $this->request->getPost('includes');
        $includes = [];
        if (is_array($rawIncludes)) {
            foreach ($rawIncludes as $item) {
                $item = trim((string)$item);
                if ($item !== '') {
                    $includes[] = $item;
                }
            }
        }

        // Process excludes array
        $rawExcludes = $this->request->getPost('excludes');
        $excludes = [];
        if (is_array($rawExcludes)) {
            foreach ($rawExcludes as $item) {
                $item = trim((string)$item);
                if ($item !== '') {
                    $excludes[] = $item;
                }
            }
        }

        $hargaQuad = $this->request->getPost('harga_quad');
        $hargaTriple = $this->request->getPost('harga_triple');
        $hargaDouble = $this->request->getPost('harga_double');
        $harga = $this->request->getPost('harga');
        if (empty($harga) && !empty($hargaQuad)) {
            $harga = $hargaQuad;
        }

        // Komisi Hierarki & Default DP
        $komisiMitra = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('komisi_mitra'));
        $komisiManager = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('komisi_manager'));
        $komisiCabangPusat = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('komisi_cabang_pusat'));
        $totalPool = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('total_pool_komisi'));
        if ($totalPool <= 0) {
            $totalPool = $komisiMitra + $komisiManager + $komisiCabangPusat;
        }
        $minDpDefault = (float)str_replace(['.', ','], ['', '.'], (string)$this->request->getPost('minimal_dp_default'));

        $dataUpdate = [
            'nama' => $this->request->getPost('nama'),
            'tanggal_keberangkatan' => $this->request->getPost('tanggal_keberangkatan') ?: null,
            'program_durasi' => $this->request->getPost('program_durasi'),
            'maskapai' => $this->request->getPost('maskapai'),
            'hotel_mekkah' => $this->request->getPost('hotel_mekkah'),
            'hotel_madinah' => $this->request->getPost('hotel_madinah'),
            'kategori' => $this->request->getPost('kategori'),
            'harga' => $harga ?: ($package['harga'] ?? 0),
            'harga_quad' => !empty($hargaQuad) ? $hargaQuad : null,
            'harga_triple' => !empty($hargaTriple) ? $hargaTriple : null,
            'harga_double' => !empty($hargaDouble) ? $hargaDouble : null,
            'komisi_mitra' => $komisiMitra,
            'komisi_manager' => $komisiManager,
            'komisi_cabang_pusat' => $komisiCabangPusat,
            'total_pool_komisi' => $totalPool,
            'minimal_dp_default' => $minDpDefault,
            'seat_tersedia' => (int)($this->request->getPost('seat_tersedia') ?? 0),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'includes' => !empty($includes) ? json_encode(array_values($includes), JSON_UNESCAPED_UNICODE) : null,
            'excludes' => !empty($excludes) ? json_encode(array_values($excludes), JSON_UNESCAPED_UNICODE) : null,
            'status' => $this->request->getPost('status') ?: ($package['status'] ?? 'aktif'),
        ];

        $newGambarName = null;
        $fileGambar = $this->request->getFile('gambar');
        $hasNewUpload = $fileGambar && $fileGambar->getError() !== UPLOAD_ERR_NO_FILE;

        if ($hasNewUpload) {
            $validationRules = [
                'gambar' => [
                    'label' => 'Gambar Paket',
                    'rules' => [
                        'uploaded[gambar]',
                        'is_image[gambar]',
                        'mime_in[gambar,image/jpg,image/jpeg,image/png,image/webp]',
                        'ext_in[gambar,jpg,jpeg,png,webp]',
                        'max_size[gambar,2048]',
                    ],
                    'errors' => [
                        'is_image' => 'File yang diunggah harus berupa gambar yang valid.',
                        'mime_in'  => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
                        'ext_in'   => 'Ekstensi file harus .jpg, .jpeg, .png, atau .webp.',
                        'max_size' => 'Ukuran gambar maksimal adalah 2MB.',
                    ],
                ],
            ];

            if (!$this->validate($validationRules)) {
                return redirect()->back()->withInput()->with('error', $this->validator->getError('gambar'));
            }

            if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
                $newGambarName = $fileGambar->getRandomName();
                try {
                    $fileGambar->move(FCPATH . 'uploads/packages', $newGambarName);
                    $dataUpdate['gambar'] = $newGambarName;
                } catch (\Throwable $e) {
                    return redirect()->back()->withInput()->with('error', 'Gagal memindahkan file gambar baru ke direktori penyimpanan.');
                }
            } else {
                return redirect()->back()->withInput()->with('error', 'File gambar baru tidak valid atau gagal diunggah.');
            }
        }

        $updateSuccess = $this->packageModel->update($id, $dataUpdate);

        if (!$updateSuccess) {
            if ($newGambarName && file_exists(FCPATH . 'uploads/packages/' . $newGambarName)) {
                @unlink(FCPATH . 'uploads/packages/' . $newGambarName);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data paket di database.');
        }

        // Hapus gambar lama hanya setelah pembaruan database berhasil
        if ($newGambarName && !empty($package['gambar']) && $package['gambar'] !== $newGambarName && file_exists(FCPATH . 'uploads/packages/' . $package['gambar'])) {
            @unlink(FCPATH . 'uploads/packages/' . $package['gambar']);
        }

        // Update Model A Aturan DP Rentang Tanggal
        $dpRuleModel = new \App\Models\PackageDpRuleModel();
        $dpRuleModel->where('package_id', (int)$id)->delete();

        $dpKeterangans = $this->request->getPost('dp_rule_keterangan');
        $dpTglMulai = $this->request->getPost('dp_rule_tgl_mulai');
        $dpTglSelesai = $this->request->getPost('dp_rule_tgl_selesai');
        $dpNominals = $this->request->getPost('dp_rule_nominal');

        if (is_array($dpNominals)) {
            for ($i = 0; $i < count($dpNominals); $i++) {
                $nom = (float)str_replace(['.', ','], ['', '.'], (string)($dpNominals[$i] ?? 0));
                $tMulai = $dpTglMulai[$i] ?? null;
                $tSelesai = $dpTglSelesai[$i] ?? null;
                $ket = trim((string)($dpKeterangans[$i] ?? ''));
                if ($nom > 0 && !empty($tMulai) && !empty($tSelesai)) {
                    $dpRuleModel->insert([
                        'package_id' => (int)$id,
                        'tanggal_mulai' => $tMulai,
                        'tanggal_selesai' => $tSelesai,
                        'nominal_dp' => $nom,
                        'keterangan' => $ket,
                    ]);
                }
            }
        }

        return redirect()->to('/superadmin/packages')->with('success', 'Perubahan data paket berhasil disimpan');
    }

    public function delete($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login')->with('error', 'Akses ditolak: Hanya Super Admin yang berhak menghapus paket');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('/superadmin/packages')->with('error', 'Metode tidak diizinkan');
        }

        $package = $this->packageModel->find($id);
        if ($package) {
            if (!empty($package['gambar']) && file_exists(FCPATH . 'uploads/packages/' . $package['gambar'])) {
                @unlink(FCPATH . 'uploads/packages/' . $package['gambar']);
            }
            $this->packageModel->delete($id);
        }

        return redirect()->back()->with('deleted', 'Data paket berhasil dihapus');
    }

    public function bulkDelete()
    {
        if (!session('logged_in') || !in_array(session('role'), ['super_admin', 'superadmin'])) {
            return redirect()->to('/login')->with('error', 'Akses ditolak: Hanya Super Admin yang berhak menghapus paket');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('/superadmin/packages')->with('error', 'Metode tidak diizinkan');
        }

        $ids = $this->request->getPost('ids');
        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada paket yang dipilih untuk dihapus');
        }

        $cleanIds = array_values(array_filter(array_map('intval', $ids), fn($id) => $id > 0));
        if (empty($cleanIds)) {
            return redirect()->back()->with('error', 'Daftar ID paket tidak valid');
        }

        $deletedCount = 0;
        foreach ($cleanIds as $delId) {
            $package = $this->packageModel->find($delId);
            if ($package) {
                if (!empty($package['gambar']) && file_exists(FCPATH . 'uploads/packages/' . $package['gambar'])) {
                    @unlink(FCPATH . 'uploads/packages/' . $package['gambar']);
                }
                $this->packageModel->delete($delId);
                $deletedCount++;
            }
        }

        return redirect()->back()->with('deleted', "Berhasil menghapus {$deletedCount} paket umroh secara massal");
    }
}
