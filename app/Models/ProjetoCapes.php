<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjetoCapes extends Model
{
    use HasFactory;

    protected $table = 'projetos_capes';

    protected $fillable = [
        'codigo',
        'programa_id',
    ];
    
    public function programas() {
        return $this->belongsTo(Programa::class);
    }
}
