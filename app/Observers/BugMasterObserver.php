<?php
namespace App\Observers;

use App\Models\BugMaster;
use App\Notifications\BugStatusChanged;
use Illuminate\Support\Facades\Notification;

class BugMasterObserver
{
    public function created(BugMaster $bug): void
    {
        // Log creation
        logger('Bug created', [
            'bug_id' => $bug->id,
            'title' => $bug->title,
            'user_id' => $bug->user_id
        ]);
    }

    public function updated(BugMaster $bug): void
    {
        if ($bug->isDirty('status')) {
            $oldStatus = $bug->getOriginal('status');
            $newStatus = $bug->status;
            
            // Notify stakeholders about status change
            $this->notifyStatusChange($bug, $oldStatus, $newStatus);
        }

        if ($bug->isDirty('assigned_to')) {
            // Log assignment change
            logger('Bug assignment changed', [
                'bug_id' => $bug->id,
                'old_assignee' => $bug->getOriginal('assigned_to'),
                'new_assignee' => $bug->assigned_to
            ]);
        }
    }

    private function notifyStatusChange(BugMaster $bug, string $oldStatus, string $newStatus): void
    {
        $recipients = collect();
        
        // Add bug reporter
        if ($bug->user && $bug->user->id !== auth()->id()) {
            $recipients->push($bug->user);
        }
        
        // Add assigned user
        if ($bug->assignedUser && $bug->assignedUser->id !== auth()->id()) {
            $recipients->push($bug->assignedUser);
        }
        
        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new BugStatusChanged($bug, $oldStatus, $newStatus));
        }
    }
}