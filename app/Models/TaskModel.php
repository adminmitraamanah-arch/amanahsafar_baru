<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'judul',
        'kategori',
        'deskripsi',
        'copywriting',
        'catatan',
        'file_path',
        'file_type',
        'file_name',
        'file_size',
        'status',
        'caption',
        'tenggat',
        'target_role',
        'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function getTaskWithFiles($taskId)
    {
        $task = $this->find($taskId);
        if (!$task) return null;

        $db = \Config\Database::connect();
        $files = $db->table('task_files')
            ->where('task_id', $taskId)
            ->get()
            ->getResultArray();

        $task['files'] = $files;
        return $task;
    }

    public function getByRole($role)
    {
        return $this->where('target_role', $role)
            ->orWhere('target_role', 'all')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
