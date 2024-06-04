<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValorTipo extends Model
{
    use HasFactory;

    protected $table = 'valor_tipos';
    protected $fillable = ['nome'];

    public function notas() {
        return $this->hasMany(Nota::class);
    }
}
