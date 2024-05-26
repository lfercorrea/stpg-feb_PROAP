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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();

            $table->text('nome')->nullable();
            $table->string('local')->nullable();
            $table->string('periodo')->nullable();
            $table->string('site_evento')->nullable();
            $table->text('titulo_trabalho')->nullable();
            $table->string('forma_participacao')->nullable();
            $table->string('valor_inscricao')->nullable();
            $table->string('valor_passagens')->nullable();
            $table->string('valor_diarias')->nullable();
            $table->text('justificativa')->nullable();
            $table->string('ja_solicitou_recurso')->nullable();
            $table->string('artigo_copia')->nullable();
            $table->string('artigo_aceite')->nullable();
            $table->text('parecer_orientador')->nullable();
            $table->string('orcamento_passagens')->nullable();
            $table->string('importacao_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
