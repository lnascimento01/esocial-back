<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dns', function (Blueprint $table) {
            $table->id();
            $table->string('dns');
            $table->bigInteger('domain_id');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('domain_id')->references('id')->on('domains');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dns', function (Blueprint $table) {
            $table->dropForeign('dns_domain_id_foreign');
            $table->dropIfExists('dns');
        });
    }
};
