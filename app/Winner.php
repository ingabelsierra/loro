<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    protected $fillable = [
        'guest_id','email','observaciones',
    ];

   
}
