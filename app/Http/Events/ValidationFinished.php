<?php

namespace App\Http\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Validation\Validator;

/**
 * バリデーション後
 */
class ValidationFinished extends AbstractEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * バリデーション後のバリデーター取得
     */
    public function getValidator(): Validator
    {
        return $this->validator;
    }
}
