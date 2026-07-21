<?php

namespace App\Livewire;

use Livewire\Component;

class BugDashboard extends Component
{
    public function render()
    {
        return view('livewire.bug-dashboard')->layout('layouts.app');
    }
}
