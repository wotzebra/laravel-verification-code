<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationCodesTable extends Migration
{
    public function up() : void
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('verifiable');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('verification_codes');
    }
}
