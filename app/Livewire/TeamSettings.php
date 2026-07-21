<?php

namespace App\Livewire;

use App\Models\SmtpSetting;
use Livewire\Component;
use App\Models\TeamSettings as TeamSettingsModels;
use Auth,Str,DB;
use Mail;

class TeamSettings extends Component
{
    public $moduleTitle=SETTING_TITLE;
    public $team_id='';
    public $attribute_set_list=[];
    public $showSuccessMessage = false;
    public $showWarningMessage = false;
    public $showErrorMessage=false;

    public $user_id;
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;

    public $app_name;
    
    public $mail_host = '';
    public $mail_port = 587;
    public $mail_username = '';
    public $mail_password = '';
    public $mail_encryption = 'tls';
    public $mail_from_address = '';
    public $mail_from_name = '';
    public $mail_signature='';
    public $success = '';
    public $error = '';
    public $testSuccess = '';
    public $testError = '';
    public $testEmail = '';
    public $mail_signature_preview;
    protected $rules = [
        'mail_host' => 'required|string',
        'mail_port' => 'required|integer',
        'mail_username' => 'required|string',
        'mail_password' => 'required|string',
        'mail_encryption' => 'required|in:tls,ssl,null',
        'mail_from_address' => 'required|email',
        'mail_from_name' => 'required|string',
    ];

    public function mount()
    {
        $this->user_id = Auth::User()->id;
        $this->team_id=Auth::user()->currentTeam->id;
        $this->team_name=Auth::user()->currentTeam->name;
        $this->commonCreateSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_DELETE_SUCCESS);

        $initialLoadData = TeamSettingsModels::where("team_id",$this->team_id)->first();
        if (!$initialLoadData) {
            $this->initialEntry();
        } else {
            $this->loadData();
        }

        $settings = SmtpSetting::where("team_id",$this->team_id)->first();
        if ($settings) {
            $this->mail_host = $settings->mail_host;
            $this->mail_port = $settings->mail_port;
            $this->mail_username = $settings->mail_username;
            $this->mail_password = $settings->mail_password;
            $this->mail_encryption = $settings->mail_encryption;
            $this->mail_from_address = $settings->mail_from_address;
            $this->mail_from_name = $settings->mail_from_name;
            $this->mail_signature=$settings->mail_signature;
        }
    }

    public function saveSettings()
    {
        $this->validate();
        
        try {
            $settings = SmtpSetting::first();
            
            if ($settings) {
                // Update existing settings
                $settings->update([
                    'mail_host' => $this->mail_host,
                    'mail_port' => $this->mail_port,
                    'mail_username' => $this->mail_username,
                    'mail_password' => $this->mail_password,
                    'mail_encryption' => $this->mail_encryption,
                    'mail_from_address' => $this->mail_from_address,
                    'mail_from_name' => $this->mail_from_name,
                    'mail_signature'=>$this->mail_signature
                ]);
            } else {
                // Create new settings
                SmtpSetting::create([
                    'mail_host' => $this->mail_host,
                    'mail_port' => $this->mail_port,
                    'mail_username' => $this->mail_username,
                    'mail_password' => $this->mail_password,
                    'mail_encryption' => $this->mail_encryption,
                    'mail_from_address' => $this->mail_from_address,
                    'mail_from_name' => $this->mail_from_name,
                    'uuid'=>Str::uuid()->toString(),
                    'team_id'=>$this->team_id,
                    'mail_signature'=>$this->mail_signature


                ]);
            }
            
            // Update the Laravel mail configuration
            config([
                'mail.mailers.smtp.host' => $this->mail_host,
                'mail.mailers.smtp.port' => $this->mail_port,
                'mail.mailers.smtp.encryption' => $this->mail_encryption,
                'mail.mailers.smtp.username' => $this->mail_username,
                'mail.mailers.smtp.password' => $this->mail_password,
                'mail.from.address' => $this->mail_from_address,
                'mail.from.name' => $this->mail_from_name,
                'mail_signature'=>$this->mail_signature
            ]);
            
            $this->success = 'SMTP settings saved successfully.';
            $this->error = '';
        } catch (\Exception $e) {
            $this->error = 'Failed to save SMTP settings: ' . $e->getMessage();
            $this->success = '';
        }
    }
    public function generatePreview()
    {
        // Basic validation to ensure we have signature content
        if (empty($this->mail_signature)) {
            // Add a flash message if signature is empty
            session()->flash('warning', 'Please enter signature content to preview');
            return;
        }
        
        // Set the preview content
        $this->mail_signature_preview = $this->mail_signature;
        
        // Optional: You could sanitize HTML here if needed
        // $this->mail_signature_preview = clean($this->mail_signature);
        
        // Optional: Flash a success message
        session()->flash('info', 'Preview generated successfully');
    }

    /**
     * Clear the signature preview
     */
    public function clearPreview()
    {
        $this->mail_signature_preview = '';
    }
    public function testConnection()
    {
        $this->validate([
            'testEmail' => 'required|email',
        ]);
        
        try {
            // Update the mail config
            config([
                'mail.mailers.smtp.host' => $this->mail_host,
                'mail.mailers.smtp.port' => $this->mail_port,
                'mail.mailers.smtp.encryption' => $this->mail_encryption,
                'mail.mailers.smtp.username' => $this->mail_username,
                'mail.mailers.smtp.password' => $this->mail_password,
                'mail.from.address' => $this->mail_from_address,
                'mail.from.name' => $this->mail_from_name,
                'mail_signature'=>$this->mail_signature
            ]);
            
            // Send a test email
            Mail::send([], [], function ($message) {
                $message->to($this->testEmail)
                        ->subject('SMTP Test Email')
                        ->html(
                            '<div style="font-family: Arial, sans-serif; color: #333;">
                                <p>This is a test email to verify your SMTP settings.</p>
                                <p>If you\'re seeing this message, your email configuration is working correctly!</p>
                                <hr style="border: 1px solid #eee; margin: 20px 0;">
                                ' . $this->mail_signature . '
                            </div>'
                        );
            });
           
            
            $this->testSuccess = 'Test email sent successfully!';
            $this->testError = '';
        } catch (\Exception $e) {
            $this->testError = 'Failed to send test email: ' . $e->getMessage();
            $this->testSuccess = '';
        }
    }


    public function validateBeforeSave()
    {

        $validationRule = [
            'app_name' => 'required',
     
        ];
        try {
            $this->validate($validationRule);
        } catch (ValidationException $validationException) {
            throw $validationException;
        }
    }


    public function initialEntry(){
        TeamSettingsModels::create([
            'app_name'=>$this->app_name??'',
            'setting_status'=>STATUS_ACTIVE,
            'uuid'=>Str::uuid()->toString(),
            'team_id'=>$this->team_id
        ]);
    }

    public function updateDataObject()
    {
        $this->validateBeforeSave();
        $existingSettings = TeamSettingsModels::where("team_id",$this->team_id)->first();

        if ($existingSettings) {
            $existingSettings->update([
                'app_name'=>$this->app_name,
                
            ]);
        }
        session()->flash('message', value: $this->commonUpdateSuccess);
        $this->dispatch('notify-success', $this->commonUpdateSuccess);
        $this->showSuccessMessage = true;
}
    public function loadData(){
        $initialLoadData = TeamSettingsModels::where("team_id",$this->team_id)->first();
        $this->app_name = $initialLoadData->app_name??'';
        
    }

    public function render()
    {
        return view('livewire.my-settings.settings')->layout('layouts.app');
    }
}