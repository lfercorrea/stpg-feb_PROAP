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
            $table->text('local')->nullable();
            $table->text('periodo')->nullable();
            $table->text('site_evento')->nullable();
            $table->text('titulo_trabalho')->nullable();
            $table->text('forma_participacao')->nullable();
            $table->text('valor_inscricao')->nullable();
            $table->text('valor_passagens')->nullable();
            $table->text('valor_diarias')->nullable();
            $table->text('justificativa')->nullable();
            $table->text('ja_solicitou_recurso')->nullable();
            $table->text('artigo_copia')->nullable();
            $table->text('artigo_aceite')->nullable();
            $table->text('parecer_orientador')->nullable();
            $table->text('orcamento_passagens')->nullable();
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
        Schema::dropIfExists('eventos');
    }
};
