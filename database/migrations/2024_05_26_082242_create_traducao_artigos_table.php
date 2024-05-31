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
        Schema::create('traducao_artigos', function (Blueprint $table) {
            $table->id();
            
            $table->text('titulo_artigo')->nullable();
            $table->text('valor')->nullable();
            $table->text('justificativa')->nullable();
            $table->text('artigo_a_traduzir')->nullable();
            $table->text('orcamento')->nullable();
            $table->text('parecer_orientador')->nullable();
            $table->text('nome_do_orientador')->nullable();
            $table->unsignedBigInteger('importacao_discentes_id')->nullable();
            $table->unsignedBigInteger('importacao_docentes_id')->nullable();
            $table->text('carimbo_data_hora');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traducao_artigos');
    }
};
