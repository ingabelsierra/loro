<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'titulo', 'descripcion', 'imagen','estado','tipo','campania_id', 'leido','link','tipo_medio', 
    ];

    public function leido() {
        return $this->hasMany('App\User_has_notifications', 'notification_id');
    }
}
