<?php

namespace App\Livewire;

use App\Models\ProductAiContentRequests;
use App\Models\ProductAiProfiles;
use Livewire\Component;
use App\Models\ProductTypeManagers as ProductTypeManagersModel;
use App\Models\TitleCollections as TitleCollectionsModels;
use Auth,Str,DB;
class Profiles extends Component
{
    public $moduleTitle=TITLE_FORMAT_TITLE;
    public $title_set_name = '';
    public $team_id='';
    public $data_set=[];
    public $showSuccessMessage = false;
    public $showWarningMessage = false;
    public $showErrorMessage=false;

    public $inputs = [];
    public $inputsValues = [];
    public $user_id;
    public $isUpdate = false;
    public $update_attribute_set_id='';
    public $showImportModal = false;

    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;

    public function mount()
    {
        $this->user_id = Auth::User()->id;
        $this->team_id=Auth::user()->currentTeam->id;
        $this->team_name=Auth::user()->currentTeam->name;
        $this->commonCreateSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_DELETE_SUCCESS);

    }



    public function saveDataObject(){

        $this->validateBeforeSave();

        $this->saveCollectionSetData();

        $this->resetForm();
        session()->flash('message', $this->commonCreateSuccess);
        $this->showSuccessMessage = true;
    }
    

    public function getData(){
        $this->data_set= ProductAiProfiles::where("team_id",$this->team_id)
        ->orderBy("id","DESC")->get();


    }
       public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function resetAllMessages()
    {
        $this->showErrorMessage = false;
        $this->showWarningMessage = false;
        $this->showSuccessMessage = false;
    }

    public function deleteProfile($uuid)
    {
        try {
            DB::beginTransaction();

            $profile = ProductAiProfiles::where("uuid", $uuid)->first();

            if (!$profile) {
                throw new \Exception('This Profile does not exist.');
            }

            $isProfileInUse = ProductAiContentRequests::where("profile_id", $profile->id)->exists();

            if ($isProfileInUse) {
                throw new \Exception('Profile is already in use. Unable to delete it.');
            }

            $profile->delete();
            DB::commit();

            $this->showSuccessMessage('Profile deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showErrorMessage($e->getMessage());
        }
    }
    private function showSuccessMessage($message)
    {
        session()->flash('message', $message);
        $this->showSuccessMessage = true;
        $this->showWarningMessage = false;
        $this->showErrorMessage = false;
    }

    private function showErrorMessage($message)
    {
        session()->flash('message', $message);
        $this->showSuccessMessage = false;
        $this->showWarningMessage = false;
        $this->showErrorMessage = true;
    }
    public function render()
    {
        $this->getData();
        return view('livewire.profile')->layout('layouts.app');
    }
}
