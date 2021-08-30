<?php

namespace App\Http\Controllers;

use App\Http\Events\ValidationFinished;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Contracts\Validation\Validator as ContractsValidator;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * バリデーション後にイベントを発火する
     */
    protected function validator(
        array $data,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ): ContractsValidator {
        return Validator::make($data, $rules, $messages, $customAttributes)
            ->after(fn ($validator) => ValidationFinished::dispatch($validator));
    }
}
