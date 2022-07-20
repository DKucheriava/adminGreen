<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    protected $table = 'collections';
    protected $fillable = ['user_id','poem_id'];

    public function userDetail(){
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function poemDetail(){
        return $this->hasOne('App\Models\Poem','id','poem_id');
    }

}
