<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poem extends Model
{
    use HasFactory;
    protected $table = 'poems';
    protected $fillable = [
        'user_id',
        'title',
        'poet',
        'description',
        'source',
        'notify_via',
        'poem_in_public_domain','approved_by_admin'
    ];



    public function poemMoodSelected(){
        return $this->hasMany('App\Models\PoemMoodSelected','poem_id','id');
    }

    public function poemThemeSelected(){
        return $this->hasMany('App\Models\PoemThemeSelected','poem_id','id');
    }

    public function additionalLinkForPoems(){
        return $this->hasMany('App\Models\AdditionalLinkForPoem','poem_id','id');
    }

}
