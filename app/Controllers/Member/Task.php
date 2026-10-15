<?php

namespace App\Controllers\Member;

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
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        // Pengguna hanya dapat melihat tugas yang telah dipublikasikan (published)
        $tasks = $this->taskModel->where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Tugas & Konten Promosi',
            'tasks' => $tasks,
        ];

        return view('member/tasks', $data);
    }

    public function download($id)
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $task = $this->taskModel->where('status', 'published')->find($id);
        if (!$task || empty($task['file_path'])) {
            return redirect()->back()->with('error', 'Konten file tidak tersedia');
        }

        MediaHelper::download($task['file_path'], $task['file_name'] ?? 'konten-' . $task['id']);
    }
}
