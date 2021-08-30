<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use Closure;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvController extends \App\Http\Controllers\Controller
{
    /**
     * ログを一覧にして表示
     */
    public function index(Request $request): View
    {
        $dirPath = storage_path("logs/*.log");
        // ファイルパスを取得、失敗した場合は空配列
        $files = glob($dirPath) ?: [];

        return view('web.csv.index', ['files' => $files]);
    }

    /**
     * 指定されたログをCSV形式でダウンロード
     */
    public function download(Request $request): StreamedResponse
    {
        $file = $request->file;
        return Response::streamDownload(function () use ($file) {

            $stream = fopen('php://output', 'w');

            if ($stream === false) {
                $message = 'CSVのダウンロードに失敗しました';
                Log::warning($message, compact('file'));
                // [TODO] 専用の例外を用意しとくべきかも
                throw new \Exception($message);
            }

            // 文字化け回避(UTF8からCP932に変換)
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');

            // CSVデータ
            // ファイルの中身を行ごとの配列にしてフィアルストリームに追加
            $jsonLogs = explode("\n", File::get($file));
            collect($jsonLogs)
                ->map($this->jsonToArray())
                ->filter($this->logByNotEmpty())
                ->map($this->logPartialToJSON())
                ->each($this->addLogToStream($stream));

            fclose($stream);
        }, basename($file) . '.csv', [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

    /**
     * JSON形式のログを配列に変換
     *
     * @return Closure(string):array 
     */
    protected function jsonToArray(): Closure
    {
        return fn ($json) => json_decode($json, true);
    }

    /**
     * 中身が存在するログで絞り込む
     * 
     * 空文字の行がnullになるので除外する処理
     *
     * @return Closure(array|null):boolean
     */
    protected function logByNotEmpty(): Closure
    {
        return fn (?array $log) => !is_null($log);
    }

    /**
     * ログの「context」と「extra」を配列からJSON形式に変換
     * 
     * @return Closure(array):array
     */
    protected function logPartialToJSON(): Closure
    {
        return function ($log) {
            $log['context'] = json_encode($log['context'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            $log['extra'] = json_encode($log['extra'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return $log;
        };
    }

    /**
     * ファイルストリームにログを追加
     * 
     * @param resource $stream
     *
     * @return Closure(array):void
     */
    protected function addLogToStream($stream): Closure
    {
        return function (array $log) use ($stream) {
            fputcsv($stream, $log);
        };
    }
}
