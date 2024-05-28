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
        Schema::create('solicitantes', function (Blueprint $table) {
            $table->id();

            $table->string('email');
            $table->string('nome');
            $table->string('tipo_solicitante');
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

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitantes');
    }
};
