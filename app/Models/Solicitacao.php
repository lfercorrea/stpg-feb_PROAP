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
        'servico_tipo_id',
        'manutencao_id',
        'outro_servico_id',
        'traducao_artigo_id',
        'importacao_discentes_id',
        'importacao_docentes_id',
        'nome_do_orientador',
        'status_id',
        'observacao',
        'carimbo_data_hora',
    ];

    public function status() {
        return $this->belongsTo(Status::class);
    }

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

    public function traducao_artigo() {
        return $this->belongsTo(TraducaoArtigo::class);
    }

    public function outro_servico() {
        return $this->belongsTo(OutroServico::class);
    }

    public function manutencao() {
        return $this->belongsTo(Manutencao::class);
    }

    public function servico_tipo() {
        return $this->belongsTo(ServicoTipo::class);
    }

    public function nota() {
        return $this->hasMany(Nota::class);
    }

    public function soma_notas() {
        return $this->nota()->sum('valor');
    }

    public static function search($search, $programa_id = null, $tipo_solicitacao = null, $status_id = null) {
        $query = self::query();

        if($search) {
            $query->whereHas('solicitante', function($query) use($search) {
                $query->where('nome', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('tipo_solicitante', 'like', '%' . $search . '%');
            });
        }
        
        if($programa_id) {
            $query->whereHas('programa', function($query) use($programa_id) {
                $query->whereIn('programa_id', $programa_id);
            });
        }

        if($tipo_solicitacao) {
            $query->whereHas('tipo', function($query) use($tipo_solicitacao) {
                $query->where('id', $tipo_solicitacao);
            });
        }

        if($status_id) {
            $query->whereHas('status', function($query) use($status_id) {
                $query->where('status_id', $status_id);
            });
        }
        
        return $query;
    }
}
