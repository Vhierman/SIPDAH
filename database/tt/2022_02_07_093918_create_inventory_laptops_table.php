<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryLaptopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_laptops', function (Blueprint $table) {
            $table->id();
            $table->integer('employees_id');
            $table->string('merk_laptop');
            $table->string('type_laptop');
            $table->string('processor');
            $table->string('ram');
            $table->string('hardisk');
            $table->string('vga');
            $table->string('sistem_operasi');
            $table->date('tanggal_penyerahan_laptop');
            $table->string('foto_laptop');
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
        Schema::dropIfExists('inventory_laptops');
    }
}
