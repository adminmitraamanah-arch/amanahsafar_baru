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

        return view('superadmin/package_form', ['title' => 'Buat Paket']);
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
        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $gambarName = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads/packages', $gambarName);
        }

        $this->packageModel->insert([
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'harga' => $this->request->getPost('harga'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar' => $gambarName,
            'status' => 'aktif',
            'created_by' => session('user_id')
        ]);

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

        return view('superadmin/package_form', ['title' => 'Edit Paket', 'package' => $package]);
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

        $dataUpdate = [
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'harga' => $this->request->getPost('harga'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        $fileGambar = $this->request->getFile('gambar');
        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $gambarName = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads/packages', $gambarName);
            $dataUpdate['gambar'] = $gambarName;

            // Hapus gambar lama jika ada
            if (!empty($package['gambar']) && file_exists(FCPATH . 'uploads/packages/' . $package['gambar'])) {
                @unlink(FCPATH . 'uploads/packages/' . $package['gambar']);
            }
        }

        $this->packageModel->update($id, $dataUpdate);

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

        $this->packageModel->delete($id);
        return redirect()->back()->with('deleted', 'Data paket berhasil dihapus');
    }
}
