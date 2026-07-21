<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Auth;
use App\Models\ProfileManagers as ProfileModel;
class ProfileManager extends Component
{
    #[Validate('required|min:3')] 
    public $database_name = '';
    public $user_id='';
    public $team_name='';
    public $team_id='';
    public $database_list=[];
    public $showSuccessMessage = false;
    public function mount()
    {
        $this->user_id = Auth::User()->id;
        $this->team_id=Auth::user()->currentTeam->id;
        $this->team_name=Auth::user()->currentTeam->name;
      
    }
    public function saveDataObject(){

        ProfileModel::create([
            'database_name' => $this->database_name,
            'team_id' => $this->team_id,
        ]);
        $this->reset(['database_name']);
        session()->flash('message', 'Database successfully created.');
        $this->showSuccessMessage = true;
    }
    
    public function getDatabases(){
        $this->database_list= ProfileModel::where('team_id', $this->team_id)->orderBy("id","DESC")->get();

    }
    public function render()
    {
        $this->getDatabases();
        return view('livewire.profile-manager')->layout('layouts.app');
    }
}
