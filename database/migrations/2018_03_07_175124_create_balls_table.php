<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balls', function (Blueprint $table) {
            $table->increments('ball_id');
            $table->integer('innings_id');
            $table->integer('player_bat');
            $table->integer('player_bowl');
            $table->string('ball_number',10);
            $table->text('incident')->nullable();
            $table->text('extra_type')->nullable();
            $table->integer('run');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balls');
    }
}
