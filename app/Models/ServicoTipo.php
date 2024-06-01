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

    public function servico() {
        return $this->hasMany(Servico::class);
    }

    public function solicitacao() {
        return $this->hasMany(Solicitacao::class);
    }
}
