<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TodoChart extends Component
{
    public $taskData;
    public $todoData;
    public $team_id;
    public function mount()
    {
        $this->team_id = Auth::user()->currentTeam->id;
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $this->taskData = User::select('users.name', DB::raw('COUNT(tasks.id) as count'))
        ->where("current_team_id",$this->team_id)
        ->whereNull("tasks.deleted_at")
        ->leftJoin('tasks', 'users.id', '=', 'tasks.assigned_to')
        ->groupBy('users.id', 'users.name')
        ->orderBy('count', 'desc')
        ->get()
        ->toArray();

    // Load Todos Data
    $this->todoData = User::select('users.name', DB::raw('COUNT(todos.id) as count'))
        ->where("current_team_id",$this->team_id)
        ->whereNull("todos.deleted_at")
        ->leftJoin('todos', 'users.id', '=', 'todos.user_id')
        ->groupBy('users.id', 'users.name')
        ->orderBy('count', 'desc')
        ->get()
        ->toArray();
    }

    public function render()
    {
        return view('livewire.user-task-graph')->layout('layouts.app');
    }
}