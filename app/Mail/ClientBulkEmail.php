<?php

namespace App\Mail;

use App\Models\Clients;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientBulkEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $customer;
    protected $emailSubject;
    protected $emailContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Clients $customer, string $subject, string $content)
    {
        $this->customer = $customer;
        $this->emailSubject = $subject;
        $this->emailContent = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->emailSubject)
                    ->view('emails.client-bulk')
                    ->with([
                        'customer' => $this->customer,
                        'content' => $this->emailContent,
                        'emailSubject'=>$this->emailSubject
                    ]);
    }
}
