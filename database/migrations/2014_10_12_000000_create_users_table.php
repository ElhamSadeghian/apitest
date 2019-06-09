<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullName');
            $table->string('phone');
            $table->string('email');
            $table->string('password');
            $table->string('registerDate');
            $table->string('avatar')->nullable();
            $table->string('birthDate')->nullable();
            $table->string('gender')->nullable();
            $table->string('zipCode')->nullable();
            $table->string('os')->nullable();
            $table->string('os_version')->nullable();
            $table->string('api_token', 60)->unique()->nullable();
            $table->rememberToken();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
