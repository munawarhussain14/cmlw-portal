<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("permissions",function(Blueprint $table){
            $table->unsignedBigInteger('module_id')->nullable()->after("slug");
            $table->foreign("module_id")->references("id")->on("modules");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function($table) {
           $table->dropForeign('permissions_module_id_foreign');
           $table->dropColumn('module_id');
       });
    }
};
