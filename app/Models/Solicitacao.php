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
        'atividade_id',
        'evento_id',
        'material_id',
        'servico_id',
        'importacao_id',
    ];

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
}
