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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();

            $table->text('descricao')->nullable();
            $table->string('valor')->nullable();
            $table->text('justificativa')->nullable();
            $table->string('ja_solicitou_recurso')->nullable();
            $table->string('orcamento')->nullable();
            $table->text('parecer_orientador')->nullable();
            $table->string('importacao_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
