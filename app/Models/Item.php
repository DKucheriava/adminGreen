<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [
        'creatorid',
        'userid',
        'cname',
        'ititle',
        'iyear',
        'itheme1',
        'itheme2',
        'itheme3',
        'itheme4',
        'itheme5',
        'imood1',
        'imood2',
        'imood3',
        'icontent_url',
        'curl',
        'item_text1',
        'item_text2',
        'item_text3',
        'iadd_url_1',
        'iadd_url_2',
        'iadd_url_3',
        'inum_words',
        'inum_words_bin',
        'inum_lines',
        'inum_words_per_line',
        'inum_words_per_line_bin',
        'approved_by_admin',
        'is_admin'
    ];

     public function itemAddedByUser(){
        return $this->hasOne('App\Models\User','userid','userid');
    }

    public function itemDetail(){
        return $this->hasOne('App\Models\PoemText','itemid','itemid');
    }

    public function poemFullDetail(){
        return $this->belongsTo('App\Models\PoemText','itemid','itemid');
    }


}


