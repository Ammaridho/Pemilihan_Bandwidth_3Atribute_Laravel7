<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class internet_keluarga extends Model
{
    protected $table = 'internet_keluarga';

    public function data_penghuni()
    {
        return $this->hasMany(data_penghuni::class);
    }
}
