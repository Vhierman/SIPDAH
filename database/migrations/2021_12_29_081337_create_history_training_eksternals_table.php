<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTrainingEksternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_training_eksternals', function (Blueprint $table) {
            $table->id();
            $table->integer('employees_id');
            $table->string('institusi_penyelenggara_training_eksternal');
            $table->string('perihal_training_eksternal');
            $table->string('hari_awal_training_eksternal');
            $table->string('hari_akhir_training_eksternal');
            $table->date('tanggal_awal_training_eksternal');
            $table->date('tanggal_akhir_training_eksternal');
            $table->time('jam_training_eksternal');
            $table->string('lokasi_training_eksternal');
            $table->text('alamat_training_eksternal');
            $table->string('nomor_surat_training_eksternal');
            $table->string('dokumen_materi_training_eksternal');
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
        Schema::dropIfExists('history_training_eksternals');
    }
}
