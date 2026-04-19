<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectHeadsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('object_heads_tables', function (Blueprint $table) {
            $table->id();
            $table->string("no");
            $table->string("title");
            $table->enum("head_type", ["none", "regular", "welfare"])->default("none");
            $table->unsignedBigInteger('object_head_id')->nullable();
            $table->integer("ref_id")->default(0);
            $table->enum("ref", ["none", "compilations", "budgets"])->default("none");
            $table->boolean("leaf")->default(false);
            $table->timestamps();
            $table->foreign('object_head_id')->references('id')->on('object_heads_tables');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('object_heads_tables');
    }
}
