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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('userId');
            $table->string('userName');
            $table->string('userFirstName')->nullable();
            $table->string('userLastName')->nullable();
            $table->string('userPhone');
            $table->string('userImage')->nullable();
            $table->string('path')->nullable();
            $table->date('userDateBirth')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};
