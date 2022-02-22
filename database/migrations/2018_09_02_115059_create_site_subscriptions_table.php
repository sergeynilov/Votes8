<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\SiteSubscription;
use App\VoteCategory;

class CreateSiteSubscriptionsTable extends Migration
{
    private $users_tb;
    private $site_subscriptions_tb;
    private $vote_categories_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->site_subscriptions_tb= with(new SiteSubscription)->getTable();
        $this->vote_categories_tb= with(new VoteCategory)->getTable();
    }
    public function up()
    {
        Schema::create($this->site_subscriptions_tb, function (Blueprint $table) {

            $table->increments('id');
            $table->string('name', 255)->unique();
            $table->boolean('active')->default(false);

            $table->integer('vote_category_id')->unsigned()->nullable();
            $table->foreign('vote_category_id')->references('id')->on($this->vote_categories_tb)->onDelete('CASCADE');

            $table->string('mailchimp_list_name', 255)->nullable();
            $table->string('mailchimp_list_id', 20)->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index(['created_at'], 'site_subscriptions_created_at_index');

            $table->index(['mailchimp_list_name'], 'site_subscriptions_mailchimp_list_name_index');
            $table->index(['mailchimp_list_id'], 'site_subscriptions_mailchimp_list_id_index');

            $table->index(['active', 'name'], 'site_subscriptions_active_name_index');
        });
        Artisan::call('db:seed', array('--class' => 'SiteSubscriptionsInitData'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->site_subscriptions_tb);
    }
}
