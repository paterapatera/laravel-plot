<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use \Illuminate\Contracts\Container\Container;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [];


    /**
     * {@inheritDoc}
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
        // 既存のExceptionを独自のExceptionに置換
        $this->exceptionMap = [
            QueryException::class => function (QueryException $e): QueryMaskException {
                return new QueryMaskException($e);
            },
        ];
    }

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
