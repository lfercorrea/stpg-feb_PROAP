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

            $table->text('status')->nullable();
            $table->text('carimbo_data_hora');
            $table->text('email');
            $table->text('programa');
            $table->text('categoria');
            $table->text('nome');
            $table->text('tipo_solicitante')->nullable();
            $table->text('cpf');
            $table->text('rg');
            $table->text('rg_data_expedicao');
            $table->text('rg_orgao_expedidor');
            $table->text('nascimento');
            $table->text('endereco_completo');
            $table->text('telefone');
            $table->text('banco');
            $table->text('banco_agencia');
            $table->text('banco_conta');
            $table->text('tipo_solicitacao');
            $table->text('evento_nome')->nullable();
            $table->text('evento_local')->nullable();
            $table->text('evento_periodo')->nullable();
            $table->text('evento_site_evento')->nullable();
            $table->text('evento_titulo_trabalho')->nullable();
            $table->text('evento_forma_participacao')->nullable();
            $table->text('evento_valor_inscricao')->nullable();
            $table->text('evento_valor_passagens')->nullable();
            $table->text('evento_valor_diarias')->nullable();
            $table->text('evento_justificativa')->nullable();
            $table->text('evento_ja_solicitou_recurso')->nullable();
            $table->text('evento_artigo_copia')->nullable();
            $table->text('evento_artigo_aceite')->nullable();
            $table->text('evento_parecer_orientador')->nullable();
            $table->text('evento_orcamento_passagens')->nullable();
            $table->text('material_descricao')->nullable();
            $table->text('material_valor')->nullable();
            $table->text('material_justificativa')->nullable();
            $table->text('material_ja_solicitou_recurso')->nullable();
            $table->text('material_orcamento')->nullable();
            $table->text('material_parecer_orientador')->nullable();
            $table->text('servico_tipo')->nullable();
            $table->text('servico_titulo_artigo')->nullable();
            $table->text('servico_valor')->nullable();
            $table->text('servico_justificativa')->nullable();
            $table->text('servico_artigo_a_traduzir')->nullable();
            $table->text('servico_orcamento')->nullable();
            $table->text('servico_parecer_orientador')->nullable();
            $table->text('atividade_descricao')->nullable();
            $table->text('atividade_local')->nullable();
            $table->text('atividade_periodo')->nullable();
            $table->text('atividade_valor_diarias')->nullable();
            $table->text('atividade_valor_passagens')->nullable();
            $table->text('atividade_justificativa')->nullable();
            $table->text('atividade_carta_convite')->nullable();
            $table->text('atividade_parecer_orientador')->nullable();
            $table->text('atividade_orcamento_passagens')->nullable();
            $table->text('nome_do_orientador')->nullable();

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
