<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('format');
            $table->string('key');
            $table->string('result');
            $table->string('name');
            $table->string('related_name');
            $table->string('short_name');
            $table->string('start_date');
            $table->string('status');
            $table->string('title');
            $table->string('venue');
            $table->string('winner');
            $table->string('team1');
            $table->string('team2');
            $table->string('status_msg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}