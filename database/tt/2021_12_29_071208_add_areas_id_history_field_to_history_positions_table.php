<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreasIdHistoryFieldToHistoryPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_positions', function (Blueprint $table) {
            //
            $table->string('areas_id_history');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_positions', function (Blueprint $table) {
            //
            $table->dropColumn('areas_id_history');
        });
    }
}
