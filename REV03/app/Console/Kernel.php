<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\UserAccessHandler\Console\DeleteEDeleteExpiredAccessCodes;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        DeleteEDeleteExpiredAccessCodes::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('word:day');
        $schedule->command('access-codes:cleanup')->daily();

//         $schedule->command('inspire')->hourly();

//        $schedule->call(function () {
//            MailController::sendMail('mehrzad.deris@gmail.com', 'تست کرون جاب', 'مهرزاد دریس', 'تست کرون جاب');
//        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        $this->load(base_path('Modules/UserAccessHandler/Console'));

        require base_path('routes/console.php');
    }
}
