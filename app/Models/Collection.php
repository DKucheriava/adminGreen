<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Collection extends Model
{
    use HasFactory;
    protected $table = 'collections';
    protected $fillable = ['user_id','item_id','added_by'];
    
    protected $appends = ['shortDescription'];

    public function getShortDescriptionAttribute(){
        return substr($this->attributes['description'], 0, 50).'....';
    }
    
    
    public function userDetail(){
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function poemDetail(){
        return $this->hasOne('App\Models\Item','itemid','item_id');
    }

    public function poemFullDetail(){
        return $this->belongsTo('App\Models\PoemText','item_id','itemid');
    }

    public function poemFullDetail1(){
        return $this->belongsTo('App\Models\PoemText','item_id','itemid');
    }

}

