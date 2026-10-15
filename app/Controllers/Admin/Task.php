<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TaskModel;
use App\Libraries\MediaHelper;

class Task extends BaseController
{
    protected $taskModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
    }

    public function index()
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        $tasks = $this->taskModel->orderBy('created_at', 'DESC')->findAll();
        $data = [
            'title' => 'Kelola Tugas',
            'tasks' => $tasks,
        ];

        return view('admin/tasks', $data);
    }

    public function store()
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $dataInsert = [
            'judul' => $this->request->getPost('judul'),
            'kategori' => $this->request->getPost('kategori') ?: 'Promosi Paket Umrah',
            'deskripsi' => $this->request->getPost('deskripsi'),
            'copywriting' => $this->request->getPost('copywriting'),
            'catatan' => $this->request->getPost('catatan'),
            'tenggat' => !empty($this->request->getPost('tenggat')) ? $this->request->getPost('tenggat') : null,
            'target_role' => $this->request->getPost('target_role') ?: 'all',
            'status' => $this->request->getPost('status') ?: 'published',
            'created_by' => session('user_id'),
        ];

        $mediaFile = $this->request->getFile('media_file');
        if ($mediaFile && $mediaFile->isValid()) {
            $uploadResult = MediaHelper::handleUpload($mediaFile);
            if (!$uploadResult['success']) {
                return redirect()->back()->withInput()->with('error', $uploadResult['error']);
            }
            $dataInsert['file_path'] = $uploadResult['file_path'];
            $dataInsert['file_type'] = $uploadResult['file_type'];
            $dataInsert['file_name'] = $uploadResult['file_name'];
            $dataInsert['file_size'] = $uploadResult['file_size'];
        }

        $this->taskModel->insert($dataInsert);

        return redirect()->to('/admin/tasks')->with('success', 'Tugas promosi baru berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->to('/admin/tasks')->with('error', 'Tugas tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Tugas',
            'task'  => $task,
        ];

        return view('admin/task_edit', $data);
    }

    public function update($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->to('/admin/tasks')->with('error', 'Tugas tidak ditemukan');
        }

        $dataUpdate = [
            'judul' => $this->request->getPost('judul'),
            'kategori' => $this->request->getPost('kategori') ?: 'Promosi Paket Umrah',
            'deskripsi' => $this->request->getPost('deskripsi'),
            'copywriting' => $this->request->getPost('copywriting'),
            'catatan' => $this->request->getPost('catatan'),
            'tenggat' => !empty($this->request->getPost('tenggat')) ? $this->request->getPost('tenggat') : null,
            'target_role' => $this->request->getPost('target_role') ?: 'all',
            'status' => $this->request->getPost('status') ?: 'published',
        ];

        $mediaFile = $this->request->getFile('media_file');
        if ($mediaFile && $mediaFile->isValid()) {
            $uploadResult = MediaHelper::handleUpload($mediaFile);
            if (!$uploadResult['success']) {
                return redirect()->back()->withInput()->with('error', $uploadResult['error']);
            }

            if (!empty($task['file_path'])) {
                MediaHelper::deleteFile($task['file_path']);
            }

            $dataUpdate['file_path'] = $uploadResult['file_path'];
            $dataUpdate['file_type'] = $uploadResult['file_type'];
            $dataUpdate['file_name'] = $uploadResult['file_name'];
            $dataUpdate['file_size'] = $uploadResult['file_size'];
        }

        $this->taskModel->update($id, $dataUpdate);

        return redirect()->to('/admin/tasks')->with('success', 'Tugas promosi berhasil diperbarui');
    }

    public function delete($id)
    {
        if (!session('logged_in') || !in_array(session('role'), ['admin', 'pusat'])) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->to('/admin/tasks')->with('error', 'Tugas tidak ditemukan');
        }

        if (!empty($task['file_path'])) {
            MediaHelper::deleteFile($task['file_path']);
        }

        $this->taskModel->delete($id);

        return redirect()->to('/admin/tasks')->with('success', 'Tugas berhasil dihapus');
    }

    public function download($id)
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $task = $this->taskModel->find($id);
        if (!$task || empty($task['file_path'])) {
            return redirect()->back()->with('error', 'Konten file tidak tersedia');
        }

        MediaHelper::download($task['file_path'], $task['file_name'] ?? 'konten-' . $task['id']);
    }
}
