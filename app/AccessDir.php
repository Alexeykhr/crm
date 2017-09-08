<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessDir extends Model
{
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }
}
