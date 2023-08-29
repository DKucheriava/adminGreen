<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;
    protected $table = 'interactions';
    protected $fillable = [
        'userid',
        'ucountry_id',
        'visitorid',
        'vcountry',
        'itemid',
        'creatorid',
        'iyear',
        'imood_ids',
        'itheme_ids',
        'itheme1',
        'itheme2',
        'itheme3',
        'itheme4',
        'itheme5',
        'imood1',
        'imood2',
        'imood3',
        'inum_words',
        'inum_words_bin',
        'inum_lines',
        'inum_words_per_line',
        'inum_words_per_line_bin',
        'rtheme',
        'rmood',
        'received_email',
        'received_push',
        'received_online',
        'view_num',
        'last_view_start',
        'last_view_end',
        'last_view_duration',
        'collection',
        'register',
        'is_admin'
    ];

}

