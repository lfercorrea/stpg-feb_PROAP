<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FontePagadora extends Model
{
    use HasFactory;

    protected $table = 'fontes_pagadoras';

    protected $fillable = [
        'nome',
    ];

    public function nota() {
        return $this->hasMany(Nota::class);
    }
}
