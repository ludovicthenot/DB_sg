<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quete extends Model
{
    protected $table = 'quetes';

    protected $fillable = [
        'nom',
        'description',
        'exclamation',
    ];

    protected function casts(): array
    {
        return [
            'exclamation' => 'boolean',
        ];
    }
}