<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombres',100);
            $table->string('apellidos',100);
            $table->string('email',150)->unique();
            $table->string('telefono',20);
            $table->enum('estado',['activo', 'inactivo',])->default('activo');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('fecha_ultima_modificacion')->useCurrent()->useCurrentOnUpdate();

            $table->index('email');
            $table->index('estado');


        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        
    }
};
