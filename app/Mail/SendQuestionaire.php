<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendQuestionaire extends Mailable
{
    use Queueable, SerializesModels;

    protected $status_array;
    public $subject = '';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($status_array)
    {
        $this->status_array = $status_array;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
                ->from( "" , "")
                ->subject($this->subject)
                ->view('emails.send_questionaire')->with([
                        'status_array' => $this->status_array,
                    ]);
    }
}
