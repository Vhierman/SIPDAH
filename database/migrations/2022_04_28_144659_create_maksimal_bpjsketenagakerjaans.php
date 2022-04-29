<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaksimalBpjsketenagakerjaans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maksimal_bpjsketenagakerjaans', function (Blueprint $table) {
            $table->id();
            $table->string('maksimalupah_bpjsketenagakerjaan');
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
        Schema::dropIfExists('maksimal_bpjsketenagakerjaans');
    }
}
