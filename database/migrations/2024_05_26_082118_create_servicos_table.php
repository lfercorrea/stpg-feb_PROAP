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
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();

            $table->string('tipo')->nullable();
            $table->text('titulo_artigo')->nullable();
            $table->string('valor')->nullable();
            $table->text('justificativa')->nullable();
            $table->string('artigo_a_traduzir')->nullable();
            $table->string('orcamento')->nullable();
            $table->text('parecer_orientador')->nullable();
            $table->string('nome_do_orientador')->nullable();
            $table->string('carimbo_data_hora');
            $table->string('importacao_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicos');
    }
};
