<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProductTypeManagers as ProductTypeManagersModel;
use App\Models\TitleCollections as TitleCollectionsModels;
use Auth,Str,DB;
class TitleCollections extends Component
{
    public $moduleTitle=TITLE_FORMAT_TITLE;
    public $title_set_name = '';
    public $team_id='';
    public $attribute_set_list=[];
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

    public function validateBeforeSave()
    {

        $validationRule = [
            'title_set_name' => 'required|min:3',
        ];
        try {
            $this->validate($validationRule);
        } catch (ValidationException $validationException) {
            throw $validationException;
        }
    }


    public function saveDataObject(){

        $this->validateBeforeSave();

        $this->saveCollectionSetData();

        $this->resetForm();
        session()->flash('message', $this->commonCreateSuccess);
        $this->showSuccessMessage = true;
    }
    public function resetForm(){
        $this->reset(['title_set_name']);
        $this->isUpdate=false;
    }


    public function saveCollectionSetData(){
        TitleCollectionsModels::create([
            'title_set_name' => $this->title_set_name,
            'team_id' => $this->team_id,
            'title_set_status'=>STATUS_ACTIVE,
            'created_by'=>$this->user_id,
            'uuid'=>Str::uuid()->toString()
        ]);
        return true;
    }

    public function getUspCollections(){
        $this->attribute_set_list= TitleCollectionsModels::withCount('collection_set')
        ->where("team_id",$this->team_id)
        ->orderBy("id","DESC")->get();


    }
    public function showImportModalLink()
    {
        $this->reset(['csvFile']);
        $this->csvData=[];
        $this->showImportModal = true;
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

    public function editAttributeSet($uuid){
        try {
            $this->resetAllMessages();
            $this->resetForm();
            $this->isUpdate=true;
            $attributeSetModelData = TitleCollectionsModels::where("uuid", $uuid)->firstOrFail();
            $this->title_set_name= $attributeSetModelData->title_set_name;

            $this->update_attribute_set_id=$uuid;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateDataObject(){
       
        $uuid=$this->update_attribute_set_id;
        $attributeSetModelData = TitleCollectionsModels::where("uuid", $uuid)->firstOrFail();
        $attributeSetModelData->title_set_name = $this->title_set_name;
        $attributeSetModelData->save();
        session()->flash('message', $this->commonUpdateSuccess);
        $this->showSuccessMessage = true;
        $this->resetForm();

    }
    public function deleteAttributeSet($uuid){
        try {
            $this->resetAllMessages();
            $attributeSetModelData = TitleCollectionsModels::where("uuid", $uuid)->firstOrFail();
            $checkIfUsed=ProductTypeManagersModel::where("attribute_set_id", $attributeSetModelData->id)->count();
            if($checkIfUsed <= 0){
                DB::beginTransaction();
                TitleCollectionsModels::where("uuid", $uuid)->delete();
                DB::commit();
                $this->showSuccessMessage = true;
                session()->flash('message', $this->commonDeleteSuccess);
            }else{
                session()->flash('message', 'This USP is already in use, please de-link it from the collection.');
                $this->showWarningMessage = true;
            }
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    public function render()
    {
        $this->getUspCollections();
        return view('livewire.title-format.title-collections')->layout('layouts.app');
    }
}
