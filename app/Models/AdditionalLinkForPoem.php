<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalLinkForPoem extends Model
{
    use HasFactory;
    protected $table = 'poem_additional_links';
    protected $fillable = [
        'poem_id','text','url'
    ];
}
