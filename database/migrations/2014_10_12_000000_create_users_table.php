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
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('password');
            $table->string('userToken')->nullable();
            $table->timestamp('lastLogin')->nullable();
            $table->boolean('isEmailVerified')->default(false);
            $table->boolean('isPhoneVerified')->default(false);

            $table->string('emailTwoFaStatus')->default('0');
            $table->string('emailTwoFaCode')->nullable();
            $table->string('emailTwoFaToken')->nullable();
            $table->timestamp('emailCodeSendAt')->nullable();

            $table->string('phoneTwoFaStatus')->default('0');
            $table->string('phoneTwoFaCode')->nullable();
            $table->string('phoneTwoFaToken')->nullable();
            $table->timestamp('phoneCodeSendAt')->nullable();
            $table->rememberToken();
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
