<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompaniesIdHistoryFieldToHistoryPositionsTable extends Migration
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
            $table->integer('companies_id_history');
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
            $table->dropColumn('companies_id_history');
        });
    }
}
