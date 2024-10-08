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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();

            $table->string('numero');
            $table->string('data');
            $table->string('descricao')->nullable();
            $table->string('valor');
            $table->unsignedBigInteger('valor_tipo_id');
            $table->unsignedBigInteger('fonte_pagadora_id');
            $table->unsignedBigInteger('solicitacao_id');
            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('projeto_capes_id');

            $table->foreign('valor_tipo_id')->references('id')->on('valor_tipos')->onDelete('cascade');
            $table->foreign('fonte_pagadora_id')->references('id')->on('fontes_pagadoras')->onDelete('cascade');
            $table->foreign('solicitacao_id')->references('id')->on('solicitacoes')->onDelete('cascade');
            $table->foreign('solicitante_id')->references('id')->on('solicitantes')->onDelete('cascade');
            $table->foreign('projeto_capes_id')->references('id')->on('projetos_capes')->onDelete('cascade');

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
