<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notas', function (Blueprint $table) {
            $table->bigInteger('valor_integer')->nullable()->after('valor');
        });

        DB::table('notas')->orderBy('id')->chunkById(1000, function ($notas) {
            foreach ($notas as $nota) {
                $valorString = $nota->valor;
                $valorInteger = 0;
                $pontos = 0;

                if (!empty($valorString)) {
                    $cleanedString = str_replace('.', '', $valorString, $pontos);                    
                    $cleanedString = trim($cleanedString);                    
                    $valorFloat = is_numeric($cleanedString) ? (float) $cleanedString : 0.0;
                    
                    $valorInteger = ($pontos > 0) ? (int) $valorFloat : (int) round($valorFloat * 100);
                }
                
                DB::table('notas')
                    ->where('id', $nota->id)
                    ->update(['valor_integer' => $valorInteger]);
            }
        });

        Schema::table('notas', function (Blueprint $table) {
            $table->dropColumn('valor');
            $table->renameColumn('valor_integer', 'valor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notas', function (Blueprint $table) {
            $table->string('valor_string')->nullable()->after('descricao');
            
             DB::table('notas')->orderBy('id')->chunkById(1000, function ($notas) {
                foreach ($notas as $nota) {
                    $valorInteger = is_numeric($nota->valor) ? (int) $nota->valor : 0;
                    $valorFloat = (float) ($valorInteger / 100);
                    $valorString = number_format($valorFloat, 2, '.', '');

                    DB::table('notas')
                        ->where('id', $nota->id)
                        ->update(['valor_string' => $valorString]);
                }
            });

            $table->dropColumn('valor');

            $table->renameColumn('valor_string', 'valor');
        });
    }
};