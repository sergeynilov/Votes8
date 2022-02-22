<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\PasswordReset;

class CreatePasswordResetsTable extends Migration
{
    private $password_resets_tb;
    public function __construct()
    {
        $this->password_resets_tb= with(new PasswordReset())->getTable();
    }
    public function up()
    {
        Schema::create($this->password_resets_tb, function (Blueprint $table) {
            $table->string('email', 100)->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->password_resets_tb);
    }
}