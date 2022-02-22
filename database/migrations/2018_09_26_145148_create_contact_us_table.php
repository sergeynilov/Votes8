<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\ContactUs;
use App\CronNotification;

class CreateContactUsTable extends Migration
{
    private $users_tb;
    private $contact_us_tb;
    private $cron_notifications_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->contact_us_tb= with(new ContactUs)->getTable();
        $this->cron_notifications_tb= with(new CronNotification)->getTable();
    }

    public function up()
    {
        Schema::create($this->contact_us_tb, function (Blueprint $table) {
            $table->increments('id');

            $table->integer('acceptor_id')->unsigned()->nullable();
            $table->foreign('acceptor_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

            $table->string('author_name');
            $table->string('author_email');
            $table->text('message');

            $table->boolean('accepted')->default(false);
            $table->timestamp('accepted_at')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->index(['acceptor_id','accepted'], 'contact_us_acceptor_id_accepted_index');
            $table->index(['author_email','accepted'], 'contact_us_author_email_accepted_index');

        });

        Schema::create($this->cron_notifications_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->string('cron_type',50);
            $table->integer('cron_object_id')->unsigned();
            $table->index(['cron_type', 'cron_object_id'], 'cron_notifications_cron_type_cron_object_id_index');
            $table->timestamp('created_at')->useCurrent();
        });

        Artisan::call('db:seed', array('--class' => 'ContactUsInitData'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->cron_notifications_tb);
        Schema::dropIfExists($this->contact_us_tb);
    }
}
