<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'data',
        'descricao',
        'valor',
        'valor_tipo',
        'fonte_pagadora',
    ];

    public function fonte_pagadora() {
        return $this->belongsTo(FontePagadora::class);
    }

    public function solicitacao() {
        return $this->belongsTo(Solicitacao::class);
    }

    public function solicitante() {
        return $this->belongsTo(Solicitante::class, 'solicitante_id');
    }

    public function valor_tipo() {
        return $this->belongsTo(ValorTipo::class, 'valor_tipo_id');
    }

    public function projeto_capes() {
        return $this->belongsTo(ProjetoCapes::class, 'projeto_capes_id');
    }
}
