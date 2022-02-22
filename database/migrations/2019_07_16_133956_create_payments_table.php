<?php

use App\Payment;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

//private $page_contents_tb;


class CreatePaymentsTable extends Migration
{
    private $users_tb;
    private $payments_tb;
    public function __construct()
    {
        $this->users_tb         = with(new User)->getTable();
        $this->payments_tb= with(new Payment)->getTable();
    }
    public function up()
    {
        Schema::create($this->payments_tb, function (Blueprint $table) {

            $table->bigIncrements('id')->unsigned();

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

            $table->enum('status', ['D', 'C', 'A'])->default("D")->comment( ' D => Draft, C=>Completed, A=>Cancelled' );
            $table->enum('payment_type', ['PC', 'PS', 'SC', 'SS'])->comment( 'PC => Paypal Checkout, PS => Paypal Subscription, SC => Stripe Checkout, SS => Stripe Subscription' );

//            $table->string('payment_type', 1);
            $table->string('payment_status', 20);
            $table->string('payment_description', 255);
            $table->string('invoice_number', 50);
            
            $table->string('payer_id', 20);
            $table->string('payer_email', 255);
            $table->string('payer_first_name', 50);
            $table->string('payer_last_name', 50);
            $table->string('payer_middle_name', 50)->nullable();
            $table->string('currency', 5);

            $table->decimal('total', 9, 2);
            $table->decimal('subtotal',9, 2);
            $table->decimal('tax', 9, 2);
            $table->decimal('shipping', 9, 2);

            $table->string('payer_shipping_address', 255);
            $table->string('payer_recipient_name', 100)->nullable();
            $table->string('payer_city', 100)->nullable();
            $table->string('payer_state', 100)->nullable();
            $table->string('payer_postal_code', 10)->nullable();
            $table->string('payer_country_code', 2)->nullable();
            $table->string('payer_business_name', 100)->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index(['created_at', 'payment_type'], 'payments_created_at_payment_type_index');
            $table->index(['payment_type', 'user_id', 'status'], 'payments_payment_type_user_status_id_index');
        });
        Artisan::call('db:seed', array('--class' => 'PaymentsTableSeeder'));


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->payments_tb);
    }
}
