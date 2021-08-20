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
        // 1時間ごとにinspireを起動、多重起動は防止
        // $schedule->command('inspire')->hourly()->withoutOverlapping();
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
