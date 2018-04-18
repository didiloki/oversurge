<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('prescriptions', function (Blueprint $table) {
        $table->integer('patient_id')->unsigned();
        $table->integer('staff_id')->unsigned();
        $table->integer('drug_id')->unsigned();

          $table->foreign('patient_id')->references('id')->on('users');
          $table->foreign('staff_id')->references('id')->on('users');
          $table->foreign('drug_id')->references('id')->on('drugs');

      });

      Schema::table('appointments', function (Blueprint $table) {
        $table->integer('patient_id')->unsigned();
        $table->integer('staff_id')->unsigned();

          $table->foreign('patient_id')->references('id')->on('users');
          $table->foreign('staff_id')->references('id')->on('users');

      });

      Schema::table('schedules', function (Blueprint $table) {

        $table->integer('staff_id')->unsigned();
        $table->foreign('staff_id')->references('id')->on('users');

      });

      Schema::table('staffs', function (Blueprint $table) {

        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users');

      });

      Schema::table('tests', function (Blueprint $table) {

        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users');

        $table->integer('staff_id')->unsigned();
        $table->foreign('staff_id')->references('id')->on('users');

        $table->integer('appointment_id')->unsigned();
        $table->foreign('appointment_id')->references('id')->on('appointments');


      });

      Schema::create('drug_prescription', function (Blueprint $table) {

        $table->integer('drug_id')->unsigned();
        $table->integer('prescription_id')->unsigned();

        $table->string('dosage');
        $table->string('reorder')->default(0);

        $table->foreign('drug_id')->references('id')->on('drugs');
        $table->foreign('prescription_id')->references('id')->on('prescriptions');

        $table->primary(['drug_id', 'prescription_id']);

      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
