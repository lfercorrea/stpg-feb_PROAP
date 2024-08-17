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
        Schema::create('projetos_capes', function (Blueprint $table) {
            $table->id();

            $table->string('codigo')->nullable();
            $table->string('verba')->nullable();
            $table->unsignedBigInteger('programa_id');

            $table->foreign('programa_id')->references('id')->on('programas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
