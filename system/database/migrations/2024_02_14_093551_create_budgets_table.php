<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 12, 2)->default(0);
            $table->string("fy_year");
            $table->enum("type", ["none", "debit", "credit"])->default("none");
            $table->unsignedBigInteger('object_head_id');
            $table->unsignedBigInteger('office_id');
            $table->unsignedBigInteger('account_id');
            $table->integer("ref_id")->default(0);
            $table->enum("ref", ["none", "compilations", "budgets"])->default("none");
            $table->string("purpose")->nullable();
            $table->timestamps();
            $table->foreign('object_head_id')->references('id')->on('object_heads_tables');
            $table->foreign('office_id')->references('id')->on('offices');
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}
