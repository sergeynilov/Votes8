<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        //This is the line of code added, at the end, we the have class name of DeleteInActiveUsers.php inside app\console\commands
//        '\App\Console\Commands\DeleteInActiveUsers',
        '\App\Console\Commands\checkNewContactUs',
        '\App\Console\Commands\checkNewUsers',
        '\App\Console\Commands\CertbotCertificateRenew',
    ];
//    protected $commands = [
//        //
//    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('checkNewContactUs:check_new_contact_us')->everyMinute();
        $schedule->command('checkNewUsers:check_new_user')->everyMinute();
        $schedule->command('CertbotCertificateRenew:check_new_user')->monthly();
//        $schedule->command('checkNewContactUs:check_new_contact_us')->hourlyAt(35);
//        $schedule->command('checkNewUsers:check_new_user')->hourlyAt(37);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
