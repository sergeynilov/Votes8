<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CertbotCertificateRenew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssl_setting:certbot_certificate_renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Certbot Certificate Renew as they are valid for 89 days only.';

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
        \Log::info("CertbotCertificateRenew was run");
        /*         $exitCode = Artisan::call('migrate:refresh', [
            '--seed' => true,
        ]);
 */
    }
}
