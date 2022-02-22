<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\library\AppCronTasks;

class checkNewUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkNewUsers:check_new_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if there are new users and send summary report to admins of the site ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /* To run in console :
php artisan checkNewUsers:check_new_user */
        $appCronTasks = new AppCronTasks();
        $appCronTasks->notifyNewUsers(true);
        \Log::info( 'checkNewUsers cron was run '. \Carbon\Carbon::now() );

    }
}
