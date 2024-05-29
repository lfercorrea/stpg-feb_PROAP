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
        'fonte_pagadora',
    ];

    public function fonte_pagadora() {
        return $this->belongsTo(FontePagadora::class);
    }

    public function solicitacao() {
        return $this->belongsTo(Solicitacao::class);
    }
}
