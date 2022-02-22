<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use App\ContactUs;
//use App\CronNotification;
//use App\library\BeautymailWrapper;
use App\library\AppCronTasks;

class checkNewContactUs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkNewContactUs:check_new_contact_us';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if there are new contact_us and send summary report to admins of the site ';

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
         $appCronTasks = new AppCronTasks();
         $appCronTasks->notifyNewContactUs(true);
         \Log::info( 'notifyNewContactUs cron was run '. \Carbon\Carbon::now() );
    }
}
