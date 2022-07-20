<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoemThemeSelected extends Model
{
    use HasFactory;
    protected $table = 'poem_theme_selected';
    protected $fillable = [
        'poem_id',
        'poem_theme_id'
    ];
    
    public function poemTheme(){
        return $this->hasOne('App\Models\PoemTheme', 'id', 'poem_theme_id');
    }
}
