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

    public function solicitacao() {
        return $this->hasMany(Solicitacao::class);
    }

    public function programa() {
        return $this->hasMany(Programa::class);
    }
}
