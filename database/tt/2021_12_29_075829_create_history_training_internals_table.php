<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTrainingInternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_training_internals', function (Blueprint $table) {
            $table->id();
            $table->integer('employees_id');
            $table->string('hari_training_internal');
            $table->date('tanggal_training_internal');
            $table->time('jam_training_internal');
            $table->string('lokasi_training_internal');
            $table->string('materi_training_internal');
            $table->string('trainer_training_internal');
            $table->string('dokumen_materi_training_internal');
            $table->softDeletes();
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
        Schema::dropIfExists('history_training_internals');
    }
}
