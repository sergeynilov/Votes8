<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\PaymentAgreement;
use App\User;

class CreatePaymentAgreementsTable extends Migration
{

    private $users_tb;
    private $payment_agreements_tb;
    public function __construct()
    {
        $this->users_tb         = with(new User)->getTable();
        $this->payment_agreements_tb= with(new PaymentAgreement)->getTable();
    }

    public function up()
    {
        Schema::create($this->payment_agreements_tb, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

//            $table->string('payment_type', 1);
            $table->enum('payment_type', ['PC', 'PS', 'SC', 'SS'])->comment( 'PC => Paypal Checkout, PS => Paypal Subscription, SC => Stripe Checkout, SS => Stripe Subscription' );

            $table->string('state', 20);
            $table->string('payment_agreement_id', 50);
            $table->mediumText('description');
            $table->mediumText('start_date');

            $table->timestamp('created_at')->useCurrent();

            $table->index(['created_at', 'payment_type'], 'payment_agreements_created_at_payment_type_index');
            $table->index(['payment_type', 'user_id', 'state'], 'payment_agreements_payment_type_user_id_state_index');
        });

        Artisan::call('db:seed', array('--class' => 'PaymentAgreementsTableSeeder'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->payment_agreements_tb);
    }
}