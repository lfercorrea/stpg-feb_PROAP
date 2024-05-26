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
        Schema::create('atividades', function (Blueprint $table) {
            $table->id();

            $table->text('descricao')->nullable();
            $table->string('local')->nullable();
            $table->string('periodo')->nullable();
            $table->string('valor_diarias')->nullable();
            $table->string('valor_passagens')->nullable();
            $table->text('justificativa')->nullable();
            $table->string('carta_convite')->nullable();
            $table->text('parecer_orientador')->nullable();
            $table->string('orcamento_passagens')->nullable();
            $table->string('nome_orientador')->nullable();
            $table->string('importacao_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atividades');
    }
};
