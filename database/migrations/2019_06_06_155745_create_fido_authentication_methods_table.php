<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFidoAuthenticationMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fido_authentication_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            //$data->credentialId
            $table->binary('credentialId')->nullable();
            //$data->credentialPublicKey
            $table->string('credentialPublicKey');	
            //$data->certificate
            $table->text('certificate');
            //$data->signatureCounter
            $table->integer('signatureCounter')->nullable();
            //$data->AAGUID
            $table->binary('AAGUID')->nullable();
            
            $table->timestamps();

            $table->unsignedBigInteger('user_id');
        });

        Schema::table('fido_authentication_methods', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fido_authentication_methods');
    }
}
