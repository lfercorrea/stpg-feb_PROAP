<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'observacao',
    ];

    public function solicitacao() {
        return $this->hasMany(Solicitacao::class);
    }
}
