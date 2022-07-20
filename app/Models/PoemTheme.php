<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoemTheme extends Model
{
    use HasFactory;
    protected $table = 'poem_themes';
    protected $fillable = [
        'name'
    ];
}
