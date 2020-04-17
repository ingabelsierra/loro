<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {

    use HasApiTokens,
        Notifiable,
        HasRoles;

    protected $fillable = [
        'name', 'email', 'password','avatar','frase','no_identificacion', 'change_pw', 'mrchispa',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gerenteCampaigns() {
        return $this->hasMany(Campaign::Class, 'gerente_id');
    }

    public function directorCampaigns() {
        return $this->hasMany(Campaign::Class, 'director_id');
    }

    public function wallet() {
        return $this->hasOne(Wallet::class);
    }

    public function solds()
    {
        return $this->hasMany(Sold::class);
    }
    public function sales(){
        return $this->hasMany(sales_mind::class,'user_id','no_identificacion');
    }
}
