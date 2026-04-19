<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeathGrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('death_grants', function (Blueprint $table) {
            $table->id();
            $table->string("cause");
            $table->unsignedBigInteger('labour_id');
            $table->unsignedBigInteger('scheme_id');
            $table->enum('status', ['none', 'pending', 'in process', 'approved', 'rejected'])->default("pending");
            $table->string("fy_year");
            $table->enum('cnic_status', ['none', "pending", 'approved', 'missing', 'not clear', 'not attested', 'in valid'])->default("pending");
            $table->enum('death_cert_status', ['none', "pending", 'approved', 'missing', 'not clear', 'not attested', 'in valid'])->default("pending");
            $table->enum('succession_status', ['none', "pending", 'approved', 'missing', 'not clear', 'not attested', 'in valid'])->default("pending");
            $table->enum('inquiry_report_status', ['none', "pending", 'approved', 'missing', 'not clear', 'not attested', 'in valid'])->default("pending");
            $table->enum('leaseholder_report_status', ['none', "pending", 'approved', 'missing', 'not clear', 'not attested', 'in valid'])->default("pending");
            $table->enum('appointment_letter_status', ['none', "pending", 'approved', 'missing', 'not clear', 'not attested', 'in valid'])->default("pending");
            $table->enum('schedule_a_status', ['none', "pending", 'approved', 'missing', 'not clear', 'not attested', 'in valid'])->default("pending");
            $table->string('remarks')->nullable();
            $table->boolean("form_received")->default(false);
            $table->unsignedBigInteger('doc_verify_by')->nullable();

            $table->foreign('labour_id')->references('l_id')->on('labours');
            $table->foreign('scheme_id')->references('id')->on('schemes');
            $table->foreign('doc_verify_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('death_grants');
    }
}
