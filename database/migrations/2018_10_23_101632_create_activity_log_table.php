<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ActivityLog;

class CreateActivityLogTable extends Migration
{
    private $activity_logs_tb;
    public function __construct()
    {
        $this->activity_logs_tb= with(new ActivityLog)->getTable();
    }
    public function up()
    {
        Schema::create($this->activity_logs_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->integer('subject_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->integer('causer_id')->nullable();
            $table->string('causer_type')->nullable();
            $table->text('properties')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index(['created_at'], config('activitylog.table_name').'_created_at_index');
            $table->index([ 'causer_type', 'causer_id', 'subject_id' ], config('activitylog.table_name').'_causer_type_causer_id_subject_id_index');
            $table->index('log_name', config('activitylog.table_name').'_log_name_index');
        });

        Artisan::call('db:seed', array('--class' => 'ActivityLogTableSeeder'));

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists($this->activity_logs_tb);
    }
}