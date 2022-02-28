<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_contracts', function (Blueprint $table) {
            $table->id();
            $table->integer('employees_id');
            $table->date('tanggal_awal_kontrak');
            $table->date('tanggal_akhir_kontrak');
            $table->string('status_kontrak_kerja');
            $table->string('masa_kontrak');
            $table->integer('jumlah_kontrak');
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
        Schema::dropIfExists('history_contracts');
    }
}
