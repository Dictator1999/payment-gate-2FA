<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserVerification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userverifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('userId')->default('0');

            $table->string('emailVerificationTool')->default(0);
            $table->timestamp('emailVerificationToolSendAt')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('phoneVerificationTool')->default(0);
            $table->timestamp('phoneVerificationToolSendAt')->nullable();
            $table->timestamp('phone_verified_at')->nullable();

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
        Schema::dropIfExists('userverification');
    }
}
