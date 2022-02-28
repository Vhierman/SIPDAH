<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEditOlehFieldToInventoryLaptopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_laptops', function (Blueprint $table) {
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
        Schema::table('inventory_laptops', function (Blueprint $table) {
            //
            $table->dropColumn('edit_oleh');
        });
    }
}
