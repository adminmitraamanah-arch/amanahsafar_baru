<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TaskModel;

class Task extends BaseController
{
    protected $taskModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
    }

    public function index()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $tasks = $this->taskModel->findAll();
        $data = [
            'title' => 'Kelola Tugas',
            'tasks' => $tasks,
        ];

        return view('admin/tasks', $data);
    }

    public function store()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $this->taskModel->insert([
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tenggat' => $this->request->getPost('tenggat'),
            'target_role' => $this->request->getPost('target_role'),
            'created_by' => session('user_id')
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil dibuat');
    }
}
