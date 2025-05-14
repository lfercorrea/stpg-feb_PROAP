<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();

            $table->string('nome');

            $table->timestamps();
        });

        $dados = [
            ['nome' => 'Aguardando deliberação do conselho'],
            ['nome' => 'Cancelada'],
            ['nome' => 'Deferida'],
            ['nome' => 'Indeferida'],
            ['nome' => 'Paga'],
            ['nome' => 'Pendente'],
        ];

        DB::table('statuses')->insert($dados);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
