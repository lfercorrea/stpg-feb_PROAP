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
        Schema::create('importacoes_discentes', function (Blueprint $table) {
            $table->id();

            $table->string('status')->nullable();
            $table->string('carimbo_data_hora');
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
            $table->text('evento_nome')->nullable();
            $table->string('evento_local')->nullable();
            $table->string('evento_periodo')->nullable();
            $table->string('evento_site_evento')->nullable();
            $table->text('evento_titulo_trabalho')->nullable();
            $table->string('evento_forma_participacao')->nullable();
            $table->string('evento_valor_inscricao')->nullable();
            $table->string('evento_valor_passagens')->nullable();
            $table->string('evento_valor_diarias')->nullable();
            $table->text('evento_justificativa')->nullable();
            $table->string('evento_ja_solicitou_recurso')->nullable();
            $table->string('evento_artigo_copia')->nullable();
            $table->string('evento_artigo_aceite')->nullable();
            $table->text('evento_parecer_orientador')->nullable();
            $table->string('evento_orcamento_passagens')->nullable();
            $table->text('material_descricao')->nullable();
            $table->string('material_valor')->nullable();
            $table->text('material_justificativa')->nullable();
            $table->string('material_ja_solicitou_recurso')->nullable();
            $table->string('material_orcamento')->nullable();
            $table->text('material_parecer_orientador')->nullable();
            $table->string('servico_tipo')->nullable();
            $table->text('servico_titulo_artigo')->nullable();
            $table->string('servico_valor')->nullable();
            $table->text('servico_justificativa')->nullable();
            $table->string('servico_artigo_a_traduzir')->nullable();
            $table->string('servico_orcamento')->nullable();
            $table->text('servico_parecer_orientador')->nullable();
            $table->text('atividade_descricao')->nullable();
            $table->string('atividade_local')->nullable();
            $table->string('atividade_periodo')->nullable();
            $table->string('atividade_valor_diarias')->nullable();
            $table->string('atividade_valor_passagens')->nullable();
            $table->text('atividade_justificativa')->nullable();
            $table->string('atividade_carta_convite')->nullable();
            $table->text('atividade_parecer_orientador')->nullable();
            $table->string('atividade_orcamento_passagens')->nullable();
            $table->string('nome_do_orientador')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('importacoes_discentes');
    }
};
