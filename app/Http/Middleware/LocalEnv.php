<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * ローカル環境でのみリクエストを実行
 */
class LocalEnv
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
        App::environment('local');
        if (!App::environment('local')) {
            return redirect('/');
        }

        return $next($request);
    }
}
