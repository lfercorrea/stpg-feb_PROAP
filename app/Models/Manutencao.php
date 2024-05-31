<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manutencao extends Model
{
    use HasFactory;

    protected $table = 'manutencoes';
    protected $guarded = [];

    public function solicitacoes() {
        return $this->hasMany(Solicitacao::class);
    }
}
