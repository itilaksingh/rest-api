<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProviderProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id_fk');
            $table->string('name');
            $table->string('description')->nullable();           
            $table->string('latitude');           
            $table->string('longitude');
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
         Schema::dropIfExists('provider_profiles');
    }
}
