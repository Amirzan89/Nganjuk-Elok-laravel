<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refresh_token', function (Blueprint $table) {
            $table->id('id_token');
            $table->string('email',45);
            $table->longText('token');
            $table->enum('device',['website','mobile']);
            $table->boolean('number',1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refresh_token');
    }
};