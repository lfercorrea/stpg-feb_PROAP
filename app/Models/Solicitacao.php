<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    use HasFactory;

    protected $table = 'solicitacoes';

    protected $fillable = [
        'solicitante_id',
        'programa_id',
        'programa_categoria_id',
        'tipo_solicitacao_id',
        'atividade_id',
        'evento_id',
        'material_id',
        'servico_id',
        'importacao_id',
        'nome_do_orientador',
        'carimbo_data_hora',
    ];

    public function tipo() {
        return $this->belongsTo(SolicitacaoTipo::class, 'tipo_solicitacao_id');
    }

    public function solicitante() {
        return $this->belongsTo(Solicitante::class);
    }

    public function programa() {
        return $this->belongsTo(Programa::class);
    }

    public function programaCategoria() {
        return $this->belongsTo(ProgramaCategoria::class, 'programa_categoria_id');
    }

    public function atividade() {
        return $this->belongsTo(Atividade::class);
    }

    public function evento() {
        return $this->belongsTo(Evento::class);
    }

    public function material() {
        return $this->belongsTo(Material::class);
    }

    public function servico() {
        return $this->belongsTo(Servico::class);
    }

    public static function search($search, $programa_id = null, $tipo_solicitacao = null) {
        $query = self::query();

        if($search) {
            $query->whereHas('solicitante', function($query) use($search) {
                $query->where('nome', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if($tipo_solicitacao) {
            $query->whereHas('tipo', function($query) use($tipo_solicitacao) {
                $query->where('id', $tipo_solicitacao);
            });
        }

        if($programa_id) {
            $query->whereHas('programa', function($query) use($programa_id) {
                $query->whereIn('programa_id', $programa_id);
            });
        }

        return $query;
    }
}
