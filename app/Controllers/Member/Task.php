<?php

namespace App\Controllers\Member;

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
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $role = session('role');
        $tasks = $this->taskModel->where('target_role', $role)->findAll();

        $data = [
            'title' => 'Tugas',
            'tasks' => $tasks,
        ];

        return view('member/tasks', $data);
    }
}
