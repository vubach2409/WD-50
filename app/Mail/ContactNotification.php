<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $userMessage;

    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->userMessage = $data['message'];
    }

    public function build()
    {
        return $this->subject('Thông báo liên hệ mới từ website')
            ->view('emails.contact_notification');
    }
}
