<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Movie extends Model
{

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
