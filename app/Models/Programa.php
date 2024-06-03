<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
    ];

    public function solicitantes() {
        return $this->belongsToMany(Solicitante::class, 'solicitacoes');
    }
}
