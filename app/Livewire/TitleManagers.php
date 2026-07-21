<?php

namespace App\Livewire;

use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Livewire\WithFileUploads;
use App\Models\TitleCollections;
use App\Models\TitleValueManagers;
use App\Models\UspManagers as UspManagersModel;
use App\Models\UspValueManagers;
use App\Models\TitleManagers as TitleManagersModel;
use App\Models\ProductTypeManagers as ProductTypeManagersModel;
use App\Models\UspCollections as UspCollectionsModel;
use Auth,Str,DB;
class TitleManagers extends Component
{
    use WithFileUploads;

    public $moduleTitle=TITLE_FORMAT_TITLE;
    public $template_title;
    public $csvData = [];
    public $product_type_title = '';
    public $usp_value = '';
    public $usp_status='';
    public $team_id='';
    private $usp_list;
    public $showSuccessMessage = false;
    public $showWarningMessage = false;
    public $showErrorMessage=false;
    public $inputs = [];
    public $inputsValues = [];
    
    public $user_id;
    public $isUpdate = false;
    public $update_object_id='';
    public $showImportModal = false;
    public $displayCsvData=[];
    public $master_collection_id;
    public $attribute_set_uuid;    
    public $title_set_name;
   public $perPage=PER_PAGE;

   public $commonCreateSuccess;
   public $commonUpdateSuccess;
   public $commonDeleteSuccess;
   public $commonNotDeleteSuccess;

   protected $rules = [
        'inputs.*' => 'required|min:3',
        'product_type_title'=> 'required|min:3',
    ];

    protected $messages = [
        'inputs.*.required' => 'Title format value is required.',
        'inputs.*.min' => 'The Title format value must be at least 3 characters. ',
    ];
    public function mount($id)
    {
        
        $this->user_id = Auth::User()->id;
        $this->team_id=Auth::user()->currentTeam->id;
        $this->team_name=Auth::user()->currentTeam->name;
        $this->inputs[] = '';
        $this->inputsValues[] = '';

        $initialValueSet=TitleCollections::where("uuid",$id)->first();
        $this->master_collection_id=$initialValueSet->id??'';
        $this->title_set_name=$initialValueSet->title_set_name??'';


        $this->commonCreateSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_DELETE_SUCCESS);
        $this->commonNotDeleteSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_DELETE_FAILURE);
    }

   
    public function saveDataObject(){

        $this->validate();
        $manager_object_id=$this->saveManagerObjectData();
        $this->saveChildObjectValue($manager_object_id);

        $this->resetForm();
        session()->flash('message', $this->commonCreateSuccess);
        $this->showSuccessMessage = true;
    }
    public function saveManagerObjectData(){
        $dataObject=TitleManagersModel::create([
            'product_type_title' => $this->product_type_title,
            'created_by'=>$this->user_id,
            'uuid'=>Str::uuid()->toString(),
            'title_collection_id'=>$this->master_collection_id,
            'title_status'=>STATUS_ACTIVE,
        ]);
        return $dataObject->id;
    }
    public function saveChildObjectValue($manager_object_id){

        foreach( $this->inputs as $key=>$input){
            TitleValueManagers::create([
                'title_manager_id' => $manager_object_id,
                'title_value' => $this->inputs[$key],
                'title_status'=>STATUS_ACTIVE,
                'created_by'=>$this->user_id,
                'uuid'=>Str::uuid()->toString()
            ]);
        }
        return true;
    }

    public function getInitialRecordSets(){
        $this->initial_record_set=TitleManagersModel::where('title_collection_id', $this->master_collection_id)->orderBy("id","DESC")->paginate($this->perPage);

    }

    public function editObject($uuid){
        try {
            $this->resetForm();
            $this->isUpdate=true;
            $attributeModelData = TitleManagersModel::where("uuid", $uuid)->firstOrFail();
            $this->product_type_title= $attributeModelData->product_type_title;
            $this->update_object_id=$uuid;

            foreach($attributeModelData->titleValues as $key=>$data){
                $this->inputs[$key]=$data->title_value;
            }

            $this->showSuccessMessage = false;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateDataObject(){
        $uuid=$this->update_object_id;

        $this->validate();

        $this->onlyDeleteOperation($uuid);

        $manager_object_id=$this->saveManagerObjectData();
        $this->saveChildObjectValue($manager_object_id);

        session()->flash('message', $this->commonUpdateSuccess);
        $this->showSuccessMessage = true;
        $this->resetForm();

    }

    public function resetForm(){
        $this->reset(['product_type_title']);
        $this->isUpdate=false;
        $this->inputs = [];
        $this->inputs[] = '';
        $this->inputsValues = [];
        $this->inputsValues[] = '';
    }

    public function deleteObject($uuid){
        try {
            DB::beginTransaction();
           
            $this->onlyDeleteOperation($uuid);

            DB::commit();
            $this->showSuccessMessage = true;
            session()->flash('message', $this->commonDeleteSuccess);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showWarningMessage = true;
            session()->flash('message', $this->commonNotDeleteSuccess);
            return false;
        }
    }


    public function onlyDeleteOperation($uuid){
        $deleteObject = TitleManagersModel::where("uuid", $uuid)->firstOrFail();
        TitleValueManagers::where("title_manager_id", $deleteObject->id)->delete();
        TitleManagersModel::where("uuid", operator: $uuid)->delete();
        return true;
    }

    public function addInput()
    {
        $this->inputs[] = '';
        $this->inputsValues[] = '';
    }

    public function removeInput($index)
    {
        unset($this->inputs[$index]);
        $this->inputs = array_values($this->inputs);

        unset($this->inputsValues[$index]);
        $this->inputsValues = array_values($this->inputsValues);
    }


    public function render()
    {
        $this->getInitialRecordSets();
        return view('livewire.title-format.title-managers',['initial_record_set_object'=>$this->initial_record_set])->layout('layouts.app');
    }
}