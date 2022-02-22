<?php

use App\ServiceSubscription;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceSubscriptionsTable extends Migration
{
    private $service_subscriptions_tb;
    public function __construct()
    {
        $this->service_subscriptions_tb= with(new ServiceSubscription)->getTable();
    }
    public function up()
    {
        Schema::create($this->service_subscriptions_tb, function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();

            $table->string('name', 100)->unique();

            $table->boolean('active')->default(false);
            $table->string('paypal_service_id', 50)->nullable();
            $table->string('stripe_plan_id', 50)->nullable();

            $table->boolean('is_premium')->default(false);
            $table->boolean('is_free')->default(false);

            $table->enum('price_period', ['D', 'W', 'M', 'Y'])->comment( ' D => Daily,  W => Weekly,  M => Monthly, A=>Yearly' );
            // private static $serviceSubscriptionPricePeriodLabelValueArray = ['D' => 'Daily', 'W' => 'Weekly', 'M' => 'Monthly', 'Y' => 'Yearly'];
            $table->decimal('price', 7)->nullable();

            $table->string('description', 120);
            $table->string('color', 7);
            $table->string('background_color', 7);
            $table->tinyInteger('subscription_weight');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->index(['created_at'], 'service_subscriptions_created_at_index');
            $table->index(['active', 'price_period'], 'service_subscriptions_active_price_period_index');
        });
        Artisan::call('db:seed', array('--class' => 'ServiceSubscriptionsWithInitData'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->service_subscriptions_tb);
    }
}
