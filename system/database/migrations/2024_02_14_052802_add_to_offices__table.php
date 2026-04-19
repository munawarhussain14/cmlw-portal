<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->string("ddo")->after("id")->nullable();
            $table->string("name")->after("id")->nullable();
            $table->string("fund_no")->after("ddo")->nullable();
            $table->string("grant_no")->after("id")->nullable();
            $table->unsignedBigInteger('org_id')->after("phone")->nullable();
            // $table->foreign('org_id')->references('id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropColumn('ddo');
            $table->dropColumn('name');
            $table->dropColumn("fund_no");
            $table->dropColumn("grant_no");
            $table->dropColumn("org_id");
        });
    }
}
