<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\ValorIntegerCast;

class ProjetoCapes extends Model
{
    use HasFactory;

    protected $table = 'projetos_capes';

    protected $fillable = [
        'codigo',
        'verba',
        'programa_id',
        'nota_id',
    ];

    protected $casts = [
        'verba' => ValorIntegerCast::class,
    ];
    
    public function programas() {
        return $this->belongsTo(Programa::class);
    }

    public function notas() {
        return $this->hasMany(Nota::class);
    }
}
