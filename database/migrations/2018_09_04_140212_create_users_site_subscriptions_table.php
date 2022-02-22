<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\UsersSiteSubscription;
use App\SiteSubscription;

class CreateUsersSiteSubscriptionsTable extends Migration
{
    private $users_tb;
    private $site_subscriptions_tb;
    private $users_site_subscriptions_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->users_site_subscriptions_tb= with(new UsersSiteSubscription)->getTable();
        $this->site_subscriptions_tb= with(new SiteSubscription)->getTable();
    }

    public function up()
    {
        Schema::create($this->users_site_subscriptions_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');
            $table->integer('site_subscription_id')->unsigned()->nullable();
            $table->foreign('site_subscription_id')->references('id')->on($this->site_subscriptions_tb)->onDelete('CASCADE');
            $table->string('mailchimp_subscription_id', 255)->nullable();

/*            //UsersSiteSubscription $ret$::Array
            (
            [id] => f9d638d91c48e4bba245b4343ef65331
            [email_address] => nilov@softreactor.com
*/

            $table->timestamp('created_at')->useCurrent();

            $table->index(['created_at'], 'users_site_subscriptions_created_at_index');
            $table->index(['mailchimp_subscription_id'], 'users_site_subscriptions_mailchimp_subscription_id_index');
            $table->unique(['user_id', 'site_subscription_id'], 'users_site_subscriptions_user_id_site_subscription_id_unique');
        });
        Artisan::call('db:seed', array('--class' => 'UsersSiteSubscriptionsInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->users_site_subscriptions_tb);
    }
}
