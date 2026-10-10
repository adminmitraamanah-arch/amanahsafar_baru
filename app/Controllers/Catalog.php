<?php

namespace App\Controllers;

use App\Models\PackageModel;

class Catalog extends BaseController
{
    protected $packageModel;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
    }

    public function index()
    {
        $packages = $this->packageModel->getActive();
        
        $data = [
            'title' => 'Katalog Paket',
            'packages' => $packages,
        ];

        return view('catalog/index', $data);
    }

    public function detail($id)
    {
        $package = $this->packageModel->find($id);
        
        if (!$package || $package['status'] !== 'aktif') {
            return redirect()->to('/catalog')->with('error', 'Paket tidak ditemukan');
        }

        $data = [
            'title' => $package['nama'],
            'package' => $package,
        ];

        return view('catalog/detail', $data);
    }
}
