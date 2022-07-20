<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoemMoodSelected extends Model
{
    use HasFactory;
    protected $table = 'poem_mood_selected';
    protected $fillable = [
        'poem_id',
        'poem_mood_id'
    ];

    public function poemMood()
    {
        return $this->hasOne('App\Models\PoemMood', 'id', 'poem_mood_id');
    }

}
