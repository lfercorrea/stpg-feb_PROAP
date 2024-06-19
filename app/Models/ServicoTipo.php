<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicoTipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
    ];

    public function servicos() {
        return $this->hasMany(Servico::class);
    }

    public function solicitacoes() {
        return $this->hasMany(Solicitacao::class);
    }
}
