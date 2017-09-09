<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessDir extends Model
{
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
