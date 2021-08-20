<?php

namespace App\Console\Commands;

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
     * コマンドのインスタンス作成
     *
     * @return void
     */
    public function __construct()
    {
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
        Log::info('[LogArchive]開始');
        $month = date('Y-m', strtotime(date('Y-m-01') . '-1 month'));
        $zipfile = storage_path("logs/log-{$month}.zip");
        Log::info('[LogArchive]ファイル名生成', compact('zipfile'));

        if (file_exists($zipfile)) {
            Log::notice('[LogArchive]すでにファイルが存在するので削除');
            unlink($zipfile);
        }

        $zip = new ZipArchive();
        Log::info('[LogArchive]Zip作成');
        if ($zip->open($zipfile, ZipArchive::CREATE) === false) {
            Log::error('[LogArchive]Zip作成失敗');
            return 1;
        }

        // 01～31日までのログファイル名作成
        $files = Collection::times(31, fn (int $num) => 'event-' . $month . '-' . sprintf('%02d', $num) . '.log')
            // ログファイル名にパスを追加
            ->map(fn (string $name) => storage_path("logs/{$name}"))
            // 存在しないファイル名は除外
            ->filter(fn (string $file) => file_exists($file));

        foreach ($files as $file) {
            Log::info('[LogArchive]Zipにファイルを追加', compact('file'));
            if ($zip->addFile($file, basename($file)) === false) {
                Log::error('[LogArchive]ファイルの追加失敗');
                $zip->close();
                return 1;
            }
        }

        $zip->close();
        Log::info('[LogArchive]終了');
        return 0;
    }
}
