<?php

use App\PaymentItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentItemsTable extends Migration
{
    private $payment_items_tb;
    public function __construct()
    {
        $this->payment_items_tb= with(new PaymentItem)->getTable();
    }
    public function up()
    {
        Schema::create($this->payment_items_tb, function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('payment_id')->unsigned();

            $table->enum('item_type', ['D', 'P'])->default("D")->comment( ' D => Download, P=>Product' );
            $table->smallInteger('quantity')->unsigned();
            $table->bigInteger('item_id')->unsigned();
            $table->decimal('price', 9, 2);

            $table->timestamp('created_at')->useCurrent();

            $table->index(['created_at', 'item_type'], 'payment_items_created_at_item_type_index');
            $table->index(['payment_id', 'item_type', 'item_id'], 'payment_items_payment_id_item_type_index');

        });
        Artisan::call('db:seed', array('--class' => 'PaymentItemsTableSeeder'));

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->payment_items_tb);
    }
}
