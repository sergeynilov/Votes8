<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        return;
        Schema::create('payment_packages', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 255)->unique();
            $table->string('stripe_plan_id', 50)->nullable();
            $table->boolean('active')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_free')->default(false);
            $table->mediumText('description')->nullable();
            $table->string('color', 7);
            $table->string('background_color', 7);
            $table->tinyInteger('subscription_weight');
            $table->decimal('price', 9, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->index(['created_at'], 'payment_packages_created_at_index');
            $table->index(['active'], 'payment_packages_active_index');
        });
        Artisan::call('db:seed', array('--class' => 'paymentPackagesWithInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return;
        Schema::dropIfExists('payment_packages');
    }
}
