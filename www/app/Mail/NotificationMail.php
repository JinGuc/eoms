<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * NotificationMail constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->data["logo"] = "data:image/png;base64,". base64_encode(file_get_contents(public_path('/images/logo.png')));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->data['subject'])
            ->to($this->data['toUser'])
            ->view('Mail.NotificationError');
    }
}
