<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaksimalBpjskesehatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maksimal_bpjskesehatans', function (Blueprint $table) {
            $table->id();
            $table->string('maksimalupah_bpjskesehatan');
            $table->string('edit_oleh');
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
        Schema::dropIfExists('maksimal_bpjskesehatans');
    }
}
