<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('firstname');
            $table->string('lastname')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('phone',32)->nullable()->default(null);
            $table->date('dob')->nullable()->default(null);
            $table->enum('gender', ['male','female']);
            $table->unsignedTinyInteger('active')->default(1);
            $table->string('password');
            $table->string('api_token',80)->unique()->nullable()->default(null);
            $table->rememberToken();
            $table->timestampTz('last_login')->nullable()->default(null);
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
        Schema::dropIfExists('users');
    }
}
