<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function notas() {
        return $this->hasMany(Nota::class);
    }

    public function soma_notas() {
        return $this->notas()->sum('valor');
    }

    public static function search($search, $start_date = null, $end_date = null, $programa_id = null, $tipo_solicitacao = null, $status_id = null) {
        $query = self::query()
            ->orderByRaw("datetime(
                    substr(carimbo_data_hora, 7, 4) || '-' || 
                    substr(carimbo_data_hora, 4, 2) || '-' || 
                    substr(carimbo_data_hora, 1, 2) || ' ' || 
                    substr(carimbo_data_hora, 12, 8)
                ) DESC"
            );

        $query->when($search, function($q) use ($search) {
            $q->where(function($q1) use ($search) {
                $q1->whereHas('solicitante', function($q2) use($search) {
                    $q2->where('nome', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('tipo_solicitante', 'like', '%' . $search . '%');
                })->orWhereHas('atividade', function($q1) use ($search) {
                    $q1->where('descricao', 'like', '%' . $search . '%');
                })->orWhereHas('evento', function($q1) use ($search) {
                    $q1->where('nome', 'like', '%' . $search . '%');
                })->orWhereHas('material', function($q1) use ($search) {
                    $q1->where('descricao', 'like', '%' . $search . '%');
                // serviços
                })->orWhereHas('manutencao', function($q1) use ($search) {
                    $q1->where('descricao', 'like', '%' . $search . '%');
                })->orWhereHas('outro_servico', function($q1) use ($search) {
                    $q1->where('descricao', 'like', '%' . $search . '%');
                })->orWhereHas('traducao_artigo', function($q1) use ($search) {
                    $q1->where('titulo_artigo', 'like', '%' . $search . '%');
                });
            });
        });

        $query->when($start_date AND $end_date, function($query) use($start_date, $end_date) {
            $obj_start_date = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay()->format('d/m/Y H:i:s');
            $obj_end_date = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay()->format('d/m/Y H:i:s');
            $query->whereRaw("datetime(
                    substr(carimbo_data_hora, 7, 4) || '-' || 
                    substr(carimbo_data_hora, 4, 2) || '-' || 
                    substr(carimbo_data_hora, 1, 2) || ' ' || 
                    substr(carimbo_data_hora, 12, 8)
                ) BETWEEN datetime(
                    substr(?, 7, 4) || '-' || 
                    substr(?, 4, 2) || '-' || 
                    substr(?, 1, 2) || ' ' || 
                    substr(?, 12, 8)
                ) AND datetime(
                    substr(?, 7, 4) || '-' || 
                    substr(?, 4, 2) || '-' || 
                    substr(?, 1, 2) || ' ' || 
                    substr(?, 12, 8)
                )",
                [$obj_start_date, $obj_start_date, $obj_start_date, $obj_start_date, $obj_end_date, $obj_end_date, $obj_end_date, $obj_end_date]
            );
        });

        $query->when($programa_id, function($q) use ($programa_id) {
            $q->whereHas('programa', function($q1) use($programa_id) {
                $q1->whereIn('programa_id', $programa_id);
            });
        });

        $query->when($tipo_solicitacao, function($q) use($tipo_solicitacao) {
            $q->whereHas('tipo', function($q1) use($tipo_solicitacao) {
                $q1->where('id', $tipo_solicitacao);
            });
        });

        $query->when($status_id, function($q) use($status_id) {
            $q->whereHas('status', function($q1) use($status_id) {
                $q1->where('status_id', $status_id);
            });
        });

        $query->with([
            'status' => function($columns) {
                $columns->select('id', 'nome');
            },
            'notas.valor_tipo' => function($columns) {
                $columns->select('id', 'valor', 'valor_tipo_id', 'nome');
            },
            'tipo' => function($columns) {
                $columns->select('id', 'nome');
            },
            'solicitante' => function($columns) {
                $columns->select('id', 'email', 'nome', 'tipo_solicitante');
            },
            'programa' => function($columns) {
                $columns->select('id', 'nome');
            },
            'programaCategoria' => function($columns) {
                $columns->select('id', 'nome');
            },
            'atividade' => function($columns) {
                $columns->select('id', 'descricao', 'periodo', 'valor_diarias', 'valor_passagens', 'carta_convite', 'parecer_orientador', 'orcamento_passagens', 'nome_do_orientador');
            },
            'evento' => function($columns) {
                $columns->select('id', 'nome', 'periodo', 'valor_diarias', 'valor_passagens', 'valor_inscricao', 'artigo_copia', 'artigo_aceite', 'parecer_orientador', 'orcamento_passagens');
            },
            'material' => function($columns) {
                $columns->select('id', 'descricao', 'valor', 'orcamento', 'parecer_orientador');
            },
            'traducao_artigo' => function($columns) {
                $columns->select('id', 'titulo_artigo', 'valor', 'artigo_a_traduzir', 'orcamento', 'parecer_orientador');
            },
            'outro_servico' => function($columns) {
                $columns->select('id', 'descricao', 'valor', 'orcamento');
            },
            'manutencao' => function($columns) {
                $columns->select('id', 'descricao', 'valor', 'orcamento');
            },
        ]);

        // $query->toSql();
        // dd($query->toSql(), $query->getBindings());
        
        return $query;
    }
}
