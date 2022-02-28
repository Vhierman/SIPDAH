<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('employees_id');
            $table->integer('divisions_id_history');
            $table->integer('positions_id_history');
            $table->date('tanggal_mutasi');
            $table->string('file_surat_mutasi');
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
        Schema::dropIfExists('history_positions');
    }
}
