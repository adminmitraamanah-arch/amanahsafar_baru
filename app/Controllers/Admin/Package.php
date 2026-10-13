<?php

namespace App\Controllers\Admin;

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
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        $packages = $this->packageModel->findAll();
        $data = [
            'title' => 'Katalog Paket',
            'packages' => $packages,
        ];

        return view('admin/packages', $data);
    }

    public function create()
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        return view('admin/package_form', ['title' => 'Buat Paket']);
    }

    public function store()
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $gambarName = null;
        $fileGambar = $this->request->getFile('gambar');
        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $gambarName = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads/packages', $gambarName);
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

        $this->packageModel->insert([
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
            'seat_tersedia' => (int)($this->request->getPost('seat_tersedia') ?? 0),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'includes' => !empty($includes) ? json_encode(array_values($includes), JSON_UNESCAPED_UNICODE) : null,
            'excludes' => !empty($excludes) ? json_encode(array_values($excludes), JSON_UNESCAPED_UNICODE) : null,
            'gambar' => $gambarName,
            'status' => $this->request->getPost('status') ?: 'aktif',
            'created_by' => session('user_id')
        ]);

        return redirect()->to('/admin/packages')->with('success', 'Data paket berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        $package = $this->packageModel->find($id);
        if (!$package) {
            return redirect()->to('/admin/packages')->with('error', 'Paket tidak ditemukan');
        }

        return view('admin/package_form', ['title' => 'Edit Paket', 'package' => $package]);
    }

    public function update($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $package = $this->packageModel->find($id);
        if (!$package) {
            return redirect()->to('/admin/packages')->with('error', 'Paket tidak ditemukan');
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
            'seat_tersedia' => (int)($this->request->getPost('seat_tersedia') ?? 0),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'includes' => !empty($includes) ? json_encode(array_values($includes), JSON_UNESCAPED_UNICODE) : null,
            'excludes' => !empty($excludes) ? json_encode(array_values($excludes), JSON_UNESCAPED_UNICODE) : null,
            'status' => $this->request->getPost('status') ?: ($package['status'] ?? 'aktif'),
        ];

        $fileGambar = $this->request->getFile('gambar');
        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $gambarName = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads/packages', $gambarName);
            $dataUpdate['gambar'] = $gambarName;

            if (!empty($package['gambar']) && file_exists(FCPATH . 'uploads/packages/' . $package['gambar'])) {
                @unlink(FCPATH . 'uploads/packages/' . $package['gambar']);
            }
        }

        $this->packageModel->update($id, $dataUpdate);

        return redirect()->to('/admin/packages')->with('success', 'Perubahan data paket berhasil disimpan');
    }

    public function delete($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('/admin/packages')->with('error', 'Metode tidak diizinkan');
        }

        $package = $this->packageModel->find($id);
        if (!$package) {
            return redirect()->to('/admin/packages')->with('error', 'Paket tidak ditemukan');
        }

        if (!empty($package['gambar']) && file_exists(FCPATH . 'uploads/packages/' . $package['gambar'])) {
            @unlink(FCPATH . 'uploads/packages/' . $package['gambar']);
        }

        $this->packageModel->delete($id);
        return redirect()->to('/admin/packages')->with('success', 'Data paket berhasil dihapus');
    }
}

