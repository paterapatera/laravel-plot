<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Throwable;
use Exception;

/**
 * SQLのパラメータをマスク処理をしたQueryException
 */
class QueryMaskException extends QueryException
{
    const CONNECTION_REFUSED_CODE = 2002;

    /**
     * @param QueryException $e
     * @return void
     */
    public function __construct(QueryException $e)
    {
        $maskBindings = $this->mask($e->getBindings());
        $previous = $e->getPrevious() ?? new Exception();
        parent::__construct($e->getSql(), $maskBindings, $previous);
    }

    /**
     * bindings内の文字列をすべてマスキング
     * 
     * nullの場合は"null"を表示する
     *
     * @param mixed[] $bindings
     * @return string[]
     */
    public function mask(array $bindings): array
    {
        return collect($bindings)
            ->map(fn (mixed $s) => is_null($s) ? 'null' : str_repeat('*', strlen($s)))
            ->all();
    }

    /**
     * ログ出力
     * 
     * DB接続ができない場合はアラートログを出力  
     * それ以外のSQLエラーはクリティカルログを出力
     *
     * @return boolean true => エラーログ抑制 | false => エラーログ出力
     */
    public function report(): bool
    {
        if ($this->getCode() === self::CONNECTION_REFUSED_CODE) {
            Log::alert($this->getMessage(), ['exception' => $this]);
        } else {
            Log::critical($this->getMessage(), ['exception' => $this]);
        }

        return true;
    }
}
