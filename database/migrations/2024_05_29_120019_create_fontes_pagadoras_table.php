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
        Schema::create('fontes_pagadoras', function (Blueprint $table) {
            $table->id();

            $table->string('nome');

            $table->timestamps();
        });
        
         $dados = [
            ['nome' => 'PROAP/AUXPE', ],
            ['nome' => 'Tesouro', ],
        ];
        
        DB::table('fontes_pagadoras')->insert($dados);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DB::table('fontes_pagadoras')->truncate();
        Schema::dropIfExists('fontes_pagadoras');
    }
};
