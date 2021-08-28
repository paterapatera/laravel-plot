<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use Closure;
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
        // ファイルパスを取得、失敗した場合は空配列
        $filePath = glob($dirPath) ?: [];
        $files = collect($filePath)
            ->filter($this->filePathByFilenamePrefix($request->prefix))
            ->all();

        return view('web.read-log.index', ['files' => $files]);
    }

    /**
     * ファイル名の前方一致でファイルパスを絞り込む
     * 
     * プレフィックスが指定されていない場合は絞り込まない  
     * ファイルのパスは絞り込みの条件から除外されている
     *
     * @param string|null $prefix 絞り込むファイル名のプレフィックス
     * @return Closure(string):boolean
     */
    protected function filePathByFilenamePrefix(?string $prefix): Closure
    {
        return function (string $filePath) use ($prefix) {
            if (is_null($prefix) || $prefix === '') {
                return true;
            } else {
                return 0 === strpos(basename($filePath), $prefix);
            }
        };
    }

    /**
     * 指定されたログを表示
     */
    public function show(Request $request): View
    {
        $file = $request->file;
        // ファイルの中身を行ごとに配列にして取得
        $logs = collect(explode("\n", File::get($file)))
            ->map($this->jsonToObject())
            ->filter($this->logByNotEmpty())
            ->filter($this->logByUid($request->uid))
            ->filter($this->logByUserId($request->user_id))
            ->filter($this->logByHashIp($request->hash_ip))
            ->all();
        return view('web.read-log.show', compact('logs', 'file'));
    }

    /**
     * JSON形式のログをオブジェクトクラスに変換
     *
     * @return Closure(string): object
     */
    protected function jsonToObject(): Closure
    {
        return fn ($json) => json_decode($json);
    }

    /**
     * 中身が存在するログで絞り込む
     * 
     * 空文字の行がnullになるので除外する処理
     *
     * @return Closure(object|null): boolean
     */
    protected function logByNotEmpty(): Closure
    {
        return fn (?object $log) => !is_null($log);
    }

    /**
     * UIDでログを絞り込む
     * 
     * UIDが指定されていない場合は絞り込まない
     *
     * @param string|null $uid 絞り込む対象
     * @return Closure(object):boolean
     */
    protected function logByUid(?string $uid): Closure
    {
        return function (object $log) use ($uid) {
            if (is_null($uid) || $uid === '') {
                return true;
            } else {
                return $uid === $log->extra->uid;
            }
        };
    }

    /**
     * UserIDでログを絞り込む
     * 
     * UserIDが指定されていない場合は絞り込まない
     *
     * @param string|null $userId 絞り込む対象
     * @return Closure(object):boolean
     */
    protected function logByUserId(?string $userId): Closure
    {
        return function (object $log) use ($userId) {
            if (is_null($userId) || $userId === '') {
                return true;
            } else {
                return $userId === $log->extra->user_id;
            }
        };
    }

    /**
     * HashIpでログを絞り込む
     * 
     * HashIpが指定されていない場合は絞り込まない
     *
     * @param string|null $hashIp 絞り込む対象
     * @return Closure(object):boolean
     */
    protected function logByHashIp(?string $hashIp): Closure
    {
        return function (object $log) use ($hashIp) {
            if (is_null($hashIp) || $hashIp === '') {
                return true;
            } else {
                return $hashIp === $log->extra->hash_ip;
            }
        };
    }
}
