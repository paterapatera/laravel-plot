<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * 既定のディレクトリ以外のコマンドの登録
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * コマンド(バッチ)のスケジュール設定
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 月初に先月のログファイルを圧縮
        $schedule->command('log:archive')->monthly()->withoutOverlapping();
    }

    /**
     * app/Console/Commandsディレクトリ内のコマンドをすべて読み込む
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
