<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
