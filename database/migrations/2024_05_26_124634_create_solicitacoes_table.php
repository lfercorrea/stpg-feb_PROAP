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

            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('programa_id');
            $table->unsignedBigInteger('programa_categoria_id');
            $table->unsignedBigInteger('tipo_solicitacao_id');
            $table->unsignedBigInteger('atividade_id')->nullable();
            $table->unsignedBigInteger('evento_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->unsignedBigInteger('servico_id')->nullable();
            $table->unsignedBigInteger('importacao_id');
            $table->string('nome_do_orientador')->nullable();
            $table->string('carimbo_data_hora');

            $table->foreign('solicitante_id')->references('id')->on('solicitantes')->onDelete('cascade');
            $table->foreign('programa_id')->references('id')->on('programas')->onDelete('cascade');
            $table->foreign('programa_categoria_id')->references('id')->on('programa_categorias')->onDelete('cascade');
            $table->foreign('tipo_solicitacao_id')->references('id')->on('solicitacao_tipos')->onDelete('cascade');
            $table->foreign('atividade_id')->references('id')->on('atividades')->onDelete('cascade');
            $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');

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
