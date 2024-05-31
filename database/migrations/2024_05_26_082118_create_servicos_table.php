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
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('servico_tipo_id')->nullable();
            $table->unsignedBigInteger('importacao_discentes_id')->nullable();
            $table->unsignedBigInteger('importacao_docentes_id')->nullable();
            $table->string('carimbo_data_hora');

            $table->timestamps();

            $table->foreign('servico_tipo_id')->references('id')->on('servico_tipos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicos');
    }
};
