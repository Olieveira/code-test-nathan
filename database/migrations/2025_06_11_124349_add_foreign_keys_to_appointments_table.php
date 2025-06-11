<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            DB::statement('ALTER TABLE appointments
            MODIFY patient_id BIGINT UNSIGNED NOT NULL,
            MODIFY doctor_id BIGINT UNSIGNED,
            MODIFY closed_by BIGINT UNSIGNED,
            MODIFY status_id BIGINT UNSIGNED NOT NULL');

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('doctor_id')->references('id')->on('users');
            $table->foreign('closed_by')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['closed_by']);
            $table->dropForeign(['status_id']);
        });
    }
}
