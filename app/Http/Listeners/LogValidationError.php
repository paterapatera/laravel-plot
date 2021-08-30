<?php

namespace App\Http\Listeners;

use App\Http\Events\ValidationFinished;
use Illuminate\Support\Facades\Log;

class LogValidationError
{
    /**
     * バリデーションエラー時のログ
     */
    public function handle(ValidationFinished $event): void
    {
        $errors = $event->getValidator()->errors()->all();
        if (!empty($errors)) {
            Log::notice('バリデーションエラー', $errors);
        }
    }
}
