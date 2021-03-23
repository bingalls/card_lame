<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    final public function up():void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('users_id');
            $table->unsignedTinyInteger('user_score')->default(0);
            $table->unsignedTinyInteger('opponent_score')->default(0);
            $table->enum('won', ['w', 'l', 't'])->default('t');
            $table->timestamps();

            $table->foreign('users_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    final public function down():void
    {
        Schema::dropIfExists('scores');
    }
}
