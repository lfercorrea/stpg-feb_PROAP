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
        Schema::create('valor_tipos', function (Blueprint $table) {
            $table->id();

            $table->string('nome');

            $table->timestamps();
        });

        $dados = [
            ['nome' => 'Combustível'],
            ['nome' => 'Diária'],
            ['nome' => 'Material'],
            ['nome' => 'Passagem aérea/transporte'],
            ['nome' => 'Pedágio'],
            ['nome' => 'Publicação'],
            ['nome' => 'Serviço'],
            ['nome' => 'Taxa de inscrição em congresso'],
        ];

        DB::table('valor_tipos')->insert($dados);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valor_tipos');
    }
};
