<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('password_resets', function (Blueprint $table) {
        $table->string('email')->index();  // Guarda el email del usuario
        $table->string('token');           // Token único temporal
        $table->timestamp('created_at')->nullable(); // Cuándo se creó
    });
}

public function down()
{
    Schema::dropIfExists('password_resets');
}

};
