<?php

namespace App\Http\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

abstract class AbstractEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $request;

    /**
     * イベントインスタンスを作成
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * リクエストの取得
     *
     * @return Request
     * @psalm-suppress PropertyNotSetInConstructor
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
