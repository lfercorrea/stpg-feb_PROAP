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
        Schema::create('outros_servicos', function (Blueprint $table) {
            $table->id();

            $table->text('descricao')->nullable();
            $table->text('valor')->nullable();
            $table->text('justificativa')->nullable();
            $table->text('orcamento')->nullable();
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
        Schema::dropIfExists('outros_servicos');
    }
};
