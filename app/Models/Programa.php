<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'coordenador',
        'projeto_capes',
        'saldo_inicial',
    ];
    
    public function solicitacoes() {
        return $this->hasMany(Solicitacao::class, 'programa_id');
    }

    public function solicitantes() {
        return $this->belongsToMany(Solicitante::class, 'solicitacoes');
    }

    public function projetos_capes() {
        return $this->hasMany(ProjetoCapes::class, 'programa_id');
    }

    public function soma_verbas() {
        return $this->projetos_capes()->sum('verba');
    }
}
