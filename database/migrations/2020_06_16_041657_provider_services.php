<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProviderServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('provider_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id_fk');
            $table->integer('service_id_fk');
            $table->string('service');           
            $table->float('price');           
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
        Schema::dropIfExists('provider_services');
    }
}
