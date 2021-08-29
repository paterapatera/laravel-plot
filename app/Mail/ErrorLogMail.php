<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorLogMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string */
    protected $content;

    /** @var string */
    protected $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $title, string $content)
    {
        $this->content = $content;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.error-log', ['content' => $this->content])->subject($this->title);
    }
}
