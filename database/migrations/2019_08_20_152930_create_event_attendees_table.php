<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventAttendeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_attendees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('event_id')->unsigned();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('CASCADE');

            $table->integer('attendee_user_id')->unsigned()->nullable();
            $table->foreign('attendee_user_id')->references('id')->on('users')->onDelete('CASCADE');

            $table->string('attendee_user_email',100)->nullable();
            $table->string('attendee_user_display_name',100)->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->index(['event_id','attendee_user_email'], 'event_attendees_event_id_attendee_user_email_index');
            $table->index(['event_id','attendee_user_display_name'], 'event_attendees_event_id_attendee_user_display_name_index');

        });
        Artisan::call('db:seed', array('--class' => 'eventAttendeesWithInitData'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_attendees');
    }
}
