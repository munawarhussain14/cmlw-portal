<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToLabourWife extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('labour_wife', function (Blueprint $table) {
            // $table->string("husband_cnic", 15);
            $table->unsignedBigInteger("husband_id");
            // $table->timestamps();
            // $table->foreign('husband_id')->references('l_id')->on('labours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('labour_wife', function (Blueprint $table) {
            // $table->dropForeign(['husband_id']);
            $table->dropColumn('husband_id');
        });
    }
}
