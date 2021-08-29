<?php

declare(strict_types=1);

namespace App\Logging\Handlers;

use App\Logging\Processors\UserIdProcessor;
use App\Mail\ErrorLogMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Monolog\Processor\UidProcessor;
use Monolog\Logger;
use Psr\Log\LogLevel;

/**
 * @phpstan-import-type Level from \Monolog\Logger
 * @phpstan-import-type LevelName from \Monolog\Logger
 */
class MailHandler extends \Monolog\Handler\MailHandler
{
    /** @var string */
    protected $to;
    /** @var string[] */
    protected $cc;
    /** @var string[] */
    protected $bcc;

    /**
     * インスタンスを作成
     *
     * @param int|string $level
     * @param boolean $bubble
     * @param string $to
     * @param string[] $cc
     * @param string[] $bcc
     * 
     * @phpstan-param Level|LevelName|LogLevel::* $level
     */
    function __construct($level = Logger::DEBUG, bool $bubble = true, string $to = '', array $cc = [], array $bcc = [])
    {
        $this->pushProcessor(new UidProcessor());
        $this->pushProcessor(new UserIdProcessor());
        $this->to = $to;
        $this->cc = $cc;
        $this->bcc = $bcc;
        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritDoc}
     */
    protected function send(string $content, array $records): void
    {
        collect($records)->each(function (array $r) use ($content) {
            try {
                $appName = env('APP_NAME', 'app name');
                Mail::to($this->to)
                    ->cc($this->cc)
                    ->bcc($this->bcc)
                    ->send(new ErrorLogMail("[{$r['level_name']}]{$appName}で問題が発生しました", $content));
            } catch (\Throwable $e) {
                Log::warning($e->getMessage());
            }
        });
    }
}
