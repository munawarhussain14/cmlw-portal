<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToDeathGrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('death_grants', function (Blueprint $table) {
            $table
                ->enum('form_b_status', ['none', "pending", 'approved', 'missing', 'not clear', 'not attested', 'in valid'])
                ->after("schedule_a_status")
                ->default("pending");
            // $table->string('remarks')->after("schedule_a_status")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('death_grants', function (Blueprint $table) {
            $table->dropColumn('form_b_status');
            // $table->dropColumn('remarks');
        });
    }
}
