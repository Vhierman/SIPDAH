<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEditOlehFieldToEmployeesOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees_outs', function (Blueprint $table) {
            //
            $table->string('edit_oleh');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees_outs', function (Blueprint $table) {
            //
            $table->dropColumn('edit_oleh');
        });
    }
}