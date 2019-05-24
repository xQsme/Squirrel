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
            $table->string('name')->unique();
            $table->string('email')->unique();
            //$table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->string('google_code')->default('');
            $table->boolean('google_authenticated')->default(false);

            $table->string('fido_code')->default('');
            $table->boolean('fido_authenticated')->default(false);

            $table->string('sms_code')->default('');
            $table->boolean('sms_authenticated')->default(false);

            $table->string('email_code')->default('');
            $table->string('email_temp_code')->default('');
            $table->boolean('email_authenticated')->default(false);

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
