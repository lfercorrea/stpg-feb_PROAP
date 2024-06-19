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

    public function solicitacoes() {
        return $this->hasMany(Solicitacao::class);
    }

    public function notas() {
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

    public static function search($search, $tipo_solicitante = null) {
        $query = self::query();
    
        if($search AND $tipo_solicitante) {
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('endereco_completo', 'like', '%' . $search . '%');
            })->where('tipo_solicitante', '=', $tipo_solicitante);
    
            return $query;
        }
    
        if($search) {
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('endereco_completo', 'like', '%' . $search . '%');
            });
        }

        if($tipo_solicitante) {
            $query->where('tipo_solicitante', $tipo_solicitante);
        }
    
        return $query;
    }    

    // obter todas as solicitaço~es com suas notas
    // public function solicitacoes_com_notas() {
    //     return $this->solicitacao()
    //         ->with('nota')
    //         ->get();
    // }
}
