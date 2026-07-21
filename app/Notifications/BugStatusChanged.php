<?php
namespace App\Notifications;

use App\Models\BugMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BugStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public BugMaster $bug,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line("Bug status has been updated for: {$this->bug->title}")
            ->line("Status changed from '{$this->oldStatus}' to '{$this->newStatus}'")
            ->line("Bug Type: {$this->bug->type}")
            ->line("Priority: {$this->bug->priority_text}")
            ->action('View Bug', route('bugs.index', ['search' => $this->bug->uuid]))
            ->line('Thank you for using our bug tracking system!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'bug_id' => $this->bug->id,
            'bug_uuid' => $this->bug->uuid,
            'bug_title' => $this->bug->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => "Bug '{$this->bug->title}' status changed from '{$this->oldStatus}' to '{$this->newStatus}'"
        ];
    }
}
