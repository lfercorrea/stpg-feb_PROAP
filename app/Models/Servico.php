<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $guarded = [
        //
    ];

    public function solicitacoes() {
        return $this->hasMany(Solicitacao::class);
    }
}
