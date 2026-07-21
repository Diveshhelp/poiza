<?php
namespace App\Livewire;

use Livewire\Component;

class MasterPage extends Component
{
    public function mount()
    {
      echo "here";
    }
    
    public function render()
    {
        return <<<'blade'
            <div>
                <p>Processing login...</p>
            </div>
        blade;
    }
}