<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Resume;
use App\Models\ResumeAttachment;
use App\Models\ResumeFollowup;
use App\Models\ResumeReference;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ResumeManager extends Component
{
    use WithFileUploads;

    public $resumes;
    public $resumeFields = [
        'candidate_name' => '',
        'email' => '',
        'candidate_code' => '',
        'mobile' => '',
        'referance_id' => '',
        'interview_time' => '',
        'current_ctc' => '',
        'expected_ctc' => '',
        'notice_period' => '',
        'domain' => '',
        'other_info' => '',
        'year_of_exp' => '',
        'status' => '0',
    ];
    public $isUpdate = false;
    public $editUuid = null;
    public $attachmentFile;
    public $followupNote;
    public $followupDate; // Default to current date and time
    public $referenceName;
    public $editId;
    public function mount()
    {
        $this->fetchResumes();
        $this->followupDate=date('d-m-Y');
    }

    public function fetchResumes()
    {
        $this->resumes = Resume::with(['references'])->latest()->get();
    }
    public function updateStatus($uuid, $status)
    {
        $resume = \App\Models\Resume::where('uuid', $uuid)->first();
        if ($resume) {
            $resume->status = $status;
            $resume->save();
            $this->fetchResumes();
        }
    }
    public function resetForm()
    {
        $this->resumeFields = [
            'candidate_name' => '',
            'email' => '',
            'candidate_code' => '',
            'mobile' => '',
            'referance_id' => '',
            'interview_time' => '',
            'current_ctc' => '',
            'expected_ctc' => '',
            'notice_period' => '',
            'domain' => '',
            'other_info' => '',
            'year_of_exp' => '',
            'status' => '0',
        ];
        $this->isUpdate = false;
        $this->editUuid = null;
        $this->attachmentFile = null;
        $this->followupNote = null;
        $this->followupDate = null;
        $this->referenceName = null;
    }

    public function saveResume()
    {
        $resume = Resume::create([
            ...$this->resumeFields,
            'uuid' => Str::uuid(),
            'created_by' => Auth::id(),
        ]);
        $this->fetchResumes();
        $this->resetForm();
    }

    public function editResume($uuid)
    {
        $resume = Resume::where('uuid', $uuid)->firstOrFail();
        $this->resumeFields = $resume->only(array_keys($this->resumeFields));
        $this->isUpdate = true;
        $this->editUuid = $uuid;
    }

    public function updateResume()
    {
        $resume = Resume::where('uuid', $this->editUuid)->firstOrFail();
        $resume->update($this->resumeFields);
        $this->fetchResumes();
        $this->resetForm();
    }

    public function deleteResume($uuid)
    {
        $resume = Resume::where('uuid', $uuid)->firstOrFail();
        $resume->attachments()->delete();
        $resume->followups()->delete();
        $resume->references()->delete();
        $resume->delete();
        $this->fetchResumes();
    }

    // Attachments
    public function addAttachment($uuid)
    {
        $resume = Resume::where('uuid', $uuid)->firstOrFail();
        if ($this->attachmentFile) {
            $path = $this->attachmentFile->store('attachments', 'public');
            ResumeAttachment::create([
                'resume_id' => $resume->id,
                'file_name' => $this->attachmentFile->getClientOriginalName(),
                'file_path' => $path,
            ]);
            $this->attachmentFile = null;
        }
    }
    public function deleteAttachment($id)
    {
        ResumeAttachment::find($id)?->delete();
    }

    // Followups
    public function addFollowup($uuid)
    {
        $resume = Resume::where('uuid', $uuid)->firstOrFail();
        if ($this->followupDate && $this->followupNote) {
            ResumeFollowup::create([
                'resume_id' => $resume->id,
                'followup_date' => $this->followupDate,
                'note' => $this->followupNote,
            ]);
            $this->followupDate = null;
            $this->followupNote = null;
        }
    }
    public function deleteFollowup($id)
    {
        ResumeFollowup::find($id)?->delete();
    }

    // References
    public function addReference($uuid)
    {
        $resume = Resume::where('uuid', $uuid)->firstOrFail();
        if ($this->referenceName) {
            ResumeReference::create([
                'resume_id' => $resume->id,
                'ref_name' => $this->referenceName,
            ]);
            $this->referenceName = null;
        }
    }
    public function deleteReference($id)
    {
        ResumeReference::find($id)?->delete();
    }
    public function setId($uuid)
    {
        $this->editUuid = $uuid;
        $this->editId=Resume::where('uuid', $uuid)->first()->id??'';
    }
    public function render()
    {
        return view('livewire.resume.resume-collections', [
            'resumes' => $this->resumes,
            'editUuid' => $this->editUuid,
        ])->layout('layouts.app');
    }
}