<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboursWorkHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labours_work_history', function (Blueprint $table) {
            $table->string('code');
            $table->string('area_code')->nullable();
            $table->unsignedBigInteger('labour_id');
            $table->unsignedBigInteger('mineral_title_id');
            $table->unsignedBigInteger('mining_area_id')->nullable();
            $table->date('start');
            $table->date('end')->nullable();

            $table->timestamps();

            $table->foreign('labour_id')->references('l_id')->on('labours');
            $table->foreign('mineral_title_id')->references('id')->on('mineral_title');
            $table->foreign('mining_area_id')->references('id')->on('mining_area');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('labours_work_history');
    }
}
