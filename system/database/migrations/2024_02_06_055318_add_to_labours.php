<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToLabours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('labours', function (Blueprint $table) {
            $table->string("father_cnic", 15)->nullable();
            $table->string("mother_name")->nullable();
            $table->string("mother_cnic", 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('labours', function (Blueprint $table) {
            $table->dropColumn('father_cnic');
            $table->dropColumn('mother_name');
            $table->dropColumn('mother_cnic');
        });
    }
}
