<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoTipo extends Model
{
    use HasFactory;

    protected $table = 'solicitacao_tipos';

    protected $fillable = [
        'nome',
    ];

    public function solicitacoes() {
        return $this->hasMany(Solicitacao::class);
    }
}
