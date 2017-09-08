<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function accessDir()
    {
        return $this->belongsTo(AccessDir::class);
    }
}
