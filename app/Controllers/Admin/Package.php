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
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $packages = $this->packageModel->findAll();
        $data = [
            'title' => 'Kelola Paket',
            'packages' => $packages,
        ];

        return view('admin/packages', $data);
    }

    public function create()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect()->to('/login');
        }

        return view('admin/package_form', ['title' => 'Buat Paket']);
    }

    public function store()
    {
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

        return redirect()->to('/admin/packages')->with('success', 'Paket berhasil dibuat');
    }

    public function edit($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $package = $this->packageModel->find($id);
        return view('admin/package_form', ['title' => 'Edit Paket', 'package' => $package]);
    }

    public function update($id)
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $package = $this->packageModel->find($id);
        if (!$package) {
            return redirect()->to('/admin/packages')->with('error', 'Paket tidak ditemukan');
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

            if (!empty($package['gambar']) && file_exists(FCPATH . 'uploads/packages/' . $package['gambar'])) {
                @unlink(FCPATH . 'uploads/packages/' . $package['gambar']);
            }
        }

        $this->packageModel->update($id, $dataUpdate);

        return redirect()->to('/admin/packages')->with('success', 'Paket berhasil diupdate');
    }
}
