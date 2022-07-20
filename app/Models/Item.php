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
        'cname',
        'ititle',
        'iyear',
        // 'itheme_ids',
        // 'imood_ids',
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
        'inum_words',
        'inum_words_bin',
        'inum_lines',
        'inum_words_per_line',
        'inum_words_per_line_bin'
    ];
}


