<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Logging\Channel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use ZipArchive;

class LogArchive extends Command
{
    /**
     * 実行時のコマンド名
     *
     * @var string
     */
    protected $signature = 'log:archive';

    /**
     * コマンドの概要
     *
     * @var string
     */
    protected $description = '先月のログをまとめてZip化する';

    /**
     * ロガー
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * コマンドのインスタンス作成
     *
     * @return void
     */
    public function __construct()
    {
        $this->logger = Log::stack([Channel::COMMAND]);
        parent::__construct();
    }

    /**
     * 実行処理
     * 
     * 先月のログファイルをまとめて1つのZIPにする
     *
     * @return int
     * @psalm-suppress UndefinedClass
     */
    public function handle()
    {
        $month = date('Y-m', strtotime(date('Y-m-01') . '-1 month'));
        $zipfile = storage_path("logs/log-{$month}.zip");
        $this->logger->info('ファイル名生成', compact('zipfile'));

        if (file_exists($zipfile)) {
            $this->logger->notice('すでにファイルが存在するので削除');
            unlink($zipfile);
        }

        $zip = new ZipArchive();
        $this->logger->info('Zip作成');
        if ($zip->open($zipfile, ZipArchive::CREATE) === false) {
            $this->logger->error('Zip作成失敗');
            return 1;
        }

        // 01～31日までのログファイル名作成
        $files = Collection::times(31, fn (int $num) => 'event-' . $month . '-' . sprintf('%02d', $num) . '.log')
            // ログファイル名にパスを追加
            ->flatMap(fn (string $name) => [
                storage_path("logs/web-{$name}"),
                storage_path("logs/command-{$name}"),
                storage_path("logs/api-{$name}"),
            ])
            // 存在しないファイル名は除外
            ->filter(fn (string $file) => file_exists($file));

        $this->logger->info('Zipにファイルを追加', compact('files'));
        foreach ($files as $file) {
            if ($zip->addFile($file, basename($file)) === false) {
                $this->logger->error('ファイルの追加失敗', compact('file'));
                $zip->close();
                return 1;
            }
        }

        $zip->close();
        return 0;
    }
}
