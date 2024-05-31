<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutroServico extends Model
{
    use HasFactory;

    protected $table = 'outros_servicos';
    protected $guarded = [];
}
