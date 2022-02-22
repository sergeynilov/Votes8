<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\YoutubeAccessTokens;

class CreateYoutubeAccessTokensTable extends Migration
{
    private $youtube_access_tokens_tb;
    public function __construct()
    {
        $this->youtube_access_tokens_tb= with(new YoutubeAccessTokens())->getTable();
    }
    public function up()
    {
        Schema::create('youtube_access_tokens', function(Blueprint $table)
        {
            $table->increments('id');
            $table->text('access_token');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('youtube_access_tokens');
    }
}