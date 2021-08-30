<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PictureController extends \App\Http\Controllers\Controller
{
    /**
     * 画像を一覧にして表示
     */
    public function index(Request $request): View
    {
        $files = collect(Storage::files(''))
            ->map(fn ($file) => Storage::url($file));

        return view('web.picture.index', ['files' => $files]);
    }

    /**
     * 画像をアップロード
     */
    public function upload(Request $request): RedirectResponse
    {
        $this->validator($request->all(), [
            'file' => 'required|file|image',
        ])->validate();

        $file = $request->file('file');
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $filename = $file->store('');
            Log::info('画像のアップロード完了', ['filename' => $filename]);
        } else {
            $message = '画像のアップロードに失敗しました';
            Log::warning($message);
            return back()->with('error', $message);
        }

        return back()->with('status', '画像のアップロードが完了しました');
    }
}
