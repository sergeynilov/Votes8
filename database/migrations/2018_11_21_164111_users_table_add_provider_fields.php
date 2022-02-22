<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class UsersTableAddProviderFields extends Migration
{
    private $users_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
    }
    public function up()
    {
        Schema::table($this->users_tb, function (Blueprint $table) {
            $table->string( 'provider_name', 50 )->nullable()->after('avatar');
            $table->string( 'provider_id', 255 )->nullable()->after('provider_name');
            $table->index(['provider_name', 'username'], 'users_provider_name_username_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->users_tb, function (Blueprint $table) {
            //
        });
    }
}
