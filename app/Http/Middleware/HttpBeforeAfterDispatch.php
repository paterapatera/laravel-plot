<?php

namespace App\Http\Middleware;

use App\Http\Events\ActionFinished;
use App\Http\Events\ActionStarting;
use Closure;
use Illuminate\Http\Request;

/**
 * リクエストの実行前と実行後にイベントを送信
 */
class HttpBeforeAfterDispatch
{
    /**
     * リクエストを処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        ActionStarting::dispatch($request);
        $response = $next($request);
        ActionFinished::dispatch($request);
        return $response;
    }
}
