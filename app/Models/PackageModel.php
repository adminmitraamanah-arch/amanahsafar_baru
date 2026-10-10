<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $table = 'packages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'nama',
        'kategori',
        'harga',
        'deskripsi',
        'gambar',
        'status',
        'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function getActive()
    {
        return $this->where('status', 'aktif')->findAll();
    }

    public function getByCategory($kategori)
    {
        return $this->where('kategori', $kategori)
            ->where('status', 'aktif')
            ->findAll();
    }
}
