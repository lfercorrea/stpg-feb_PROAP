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
        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->id();

            $table->string('status')->nullable();
            $table->string('email');
            $table->string('programa');
            $table->string('categoria');
            $table->string('nome');
            $table->string('cpf');
            $table->string('rg');
            $table->string('rg_data_expedicao');
            $table->string('rg_orgao_expedidor');
            $table->string('nascimento');
            $table->text('endereco_completo');
            $table->string('telefone');
            $table->string('banco');
            $table->string('banco_agencia');
            $table->string('banco_conta');
            $table->string('tipo_solicitacao');
            $table->text('nome_evento');
            $table->string('local');
            $table->string('periodo');
            $table->string('site_evento');
            $table->string('titulo_trabalho');
            $table->string('forma_participacao');
            $table->string('valor_inscricao')->nullable();
            $table->string('valor_passagens')->nullable();
            $table->string('valor_diarias')->nullable();
            $table->text('justificativa')->nullable();
            $table->string('ja_solicitou_recurso')->nullable();
            $table->string('artigo_copia')->nullable();
            $table->string('artigo_aceite')->nullable();
            $table->string('orcamento_passagens')->nullable();
            $table->string('material_descricao')->nullable();
            $table->string('material_valor')->nullable();
            $table->string('material_justificativa')->nullable();
            $table->string('material_ja_solicitou_recurso')->nullable();
            $table->string('material_orcamento')->nullable();
            $table->string('servico_tipo')->nullable();
            $table->string('servico_titulo_artigo')->nullable();
            $table->string('servico_titulo_artigo')->nullable();
            $table->string('servico_valor')->nullable();
            $table->string('servico_justificativa')->nullable();
            $table->string('servico_copia_artigo')->nullable();
            $table->string('servico_orcamento')->nullable();
            $table->string('servico_descricao')->nullable();
            $table->string('servico_numero_patrimonio')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes');
    }
};
