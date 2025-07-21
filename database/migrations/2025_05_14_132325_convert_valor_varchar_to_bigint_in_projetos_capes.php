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
        Schema::table('projetos_capes', function (Blueprint $table) {
            $table->bigInteger('verba_integer')->nullable()->after('codigo');
        });

        DB::table('projetos_capes')->orderBy('id')->chunkById(1000, function ($projetos) {
            foreach ($projetos as $projeto) {
                $verbaString = $projeto->verba;
                $verbaInteger = 0;
                $pontos = 0;

                if (!empty($verbaString)) {
                    $cleanedString = str_replace('.', '', $verbaString, $pontos);                    
                    $cleanedString = trim($cleanedString);                    
                    $verbaFloat = is_numeric($cleanedString) ? (float) $cleanedString : 0.0;
                    
                    $verbaInteger = ($pontos > 0) ? (int) $verbaFloat : (int) round($verbaFloat * 100);
                }
                
                DB::table('projetos_capes')
                    ->where('id', $projeto->id)
                    ->update(['verba_integer' => $verbaInteger]);
            }
        });

        Schema::table('projetos_capes', function (Blueprint $table) {
            $table->dropColumn('verba');
            $table->renameColumn('verba_integer', 'verba');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projetos_capes', function (Blueprint $table) {
            $table->string('verba_string')->nullable()->after('descricao');
            
             DB::table('projetos_capes')->orderBy('id')->chunkById(1000, function ($projetos) {
                foreach ($projetos as $projeto) {
                    $verbaInteger = is_numeric($projeto->verba) ? (int) $projeto->verba : 0;
                    $verbaFloat = (float) ($verbaInteger / 100);
                    $verbaString = number_format($verbaFloat, 2, '.', '');

                    DB::table('projetos_capes')
                        ->where('id', $projeto->id)
                        ->update(['verba_string' => $verbaString]);
                }
            });

            $table->dropColumn('verba');

            $table->renameColumn('verba_string', 'verba');
        });
    }
};