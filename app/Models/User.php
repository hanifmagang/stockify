<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;



class User extends Authenticatable{

    use HasFactory;
    protected $table = 'users';

     protected $fillable = [

        'name',
        'email',
        'password',
        'role'
    ];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
    public function activities(){
        return $this->hasMany(Activity::class);
    }

}
