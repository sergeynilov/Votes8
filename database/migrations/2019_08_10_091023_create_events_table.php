<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_name', 255)->unique();
            $table->string('slug', 260)->unique();
            $table->boolean('published')->default(false);

            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->enum('status', ['N', 'M', 'U'])->comment(' N=>New,  M=>Modified, U=>Unmodified')->default('N');
            $table->enum('type', ['O', 'D', 'C', 'S', 'R'])->comment( 'O=>Office, D=> Out of doors, C=> City, S => Concert/Stadium, R => Restaurant/Cafe');
            $table->boolean('is_public')->default(false);
            // In short: lat DECIMAL(10, 8) NOT NULL, lng DECIMAL(11, 8) NOT NULL
            $table->string('location', 100)->nullable();
            $table->mediumText('description')->nullable();
            $table->decimal('latitude', 10,8)->nullable();
            $table->decimal('longitude', 11,8)->nullable();

            $table->string('calendar_event_id', 100)->nullable();
            $table->string('calendar_event_html_Link', 255)->nullable();
            
            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->index(['is_public', 'published'], 'events_is_public_published_index');

            $table->index(['status', 'is_public', 'start_date'], 'events_status_is_public_start_date_index');
            $table->index(['start_date', 'end_date', 'status'], 'events_start_date_end_date_status_index');
            $table->index(['calendar_event_id', 'status'], 'events_calendar_event_id_status_index');
        });
        Artisan::call('db:seed', array('--class' => 'eventsWithInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
