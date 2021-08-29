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
    protected $levelName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $levelName, string $content)
    {
        $this->content = $content;
        $this->levelName = $levelName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "[{$this->levelName}]" . env('APP_NAME', 'app name') . 'で問題が発生しました';
        return $this
            ->markdown('email.error-log', ['content' => $this->content])
            ->subject($subject);
    }
}
