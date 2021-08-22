<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReadLogController extends \App\Http\Controllers\Controller
{
    /**
     * ログを一覧にして表示
     */
    public function index(Request $request): View
    {
        $dirPath = storage_path("logs/*.log");
        // ファイルを取得、失敗した場合は空配列
        $files = collect(glob($dirPath) ?: [])
            // プレフィックスで検索していた場合は結果を絞り込む
            ->filter(fn (string $file) => empty($request->prefix) ? true : 0 === strpos(basename($file), $request->prefix))
            ->all();

        return view('web.read-log.index', ['files' => $files]);
    }

    /**
     * 指定されたログを表示
     */
    public function show(Request $request): View
    {
        $file = $request->file;
        // ファイルの中身を行ごとに配列にして取得
        $logs = collect(explode("\n", File::get($file)))
            // 行の内容をJsonから配列に変換
            ->map(fn ($json) => json_decode($json))
            // 空文字の行がnullになるので除外
            ->filter(fn ($data) => !is_null($data))
            // contextをJSON形式で出力用に整形して入れ直す
            ->map(fn (object $data) => (object)array_merge(
                (array)$data,
                ['context' => json_encode($data->context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)]
            ))
            // UIDで検索していた場合は結果を絞り込む
            ->filter(fn (object $data) => empty($request->uid) ? true : $request->uid === $data->extra->uid)
            //User IDで検索していた場合は結果を絞り込む
            ->filter(fn (object $data) => empty($request->user_id) ? true : $request->user_id === strval($data->extra->user_id))
            ->all();
        return view('web.read-log.show', compact('logs', 'file'));
    }
}
