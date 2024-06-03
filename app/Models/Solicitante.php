<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    use HasFactory;

    protected $guarded = [
        //
    ];

    public function solicitacao() {
        return $this->hasMany(Solicitacao::class);
    }

    public function nota() {
        return $this->hasMany(Nota::class);
    }

    public function programas() {
        return $this->belongsToMany(Programa::class, 'solicitacoes');
    }

    // calcular o total de solicitações de um solitiante
    public function total_solicitacoes() {
        return $this->solicitacoes()
            ->count();
    }

    // calcular a soma dos valores de todas as notas da solicitaçaõ
    public function soma_notas() {
        return $this->solicitacao()
            ->join('notas', 'solicitacoes.id', '=', 'notas.solicitacao_id')
            ->sum('notas.valor');
    }

    // obter todas as solicitaço~es com suas notas
    // public function solicitacoes_com_notas() {
    //     return $this->solicitacao()
    //         ->with('nota')
    //         ->get();
    // }
}
