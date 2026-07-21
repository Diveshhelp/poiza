<?php

namespace App\Mail;

use App\Models\Documents;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentAttachmentsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $subjects,
        public string $messageBody,
        public array $attachmentPaths,
        public array $originalNames
    ) {}

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.attachments',
            with: [
                'subjects' => "Important: ". $this->subjects,
                'messageBody' => $this->messageBody,
                'originalNames' => $this->originalNames,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        
        foreach ($this->attachmentPaths as $path) {
            $attachments[] = Attachment::fromPath($path);
        }
        
        return $attachments;
    }
}