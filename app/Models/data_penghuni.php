<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_penghuni extends Model
{
    protected $table = 'data_penghuni';

    public $timestamps = false;

    public function internet_keluarga()
    {
        return $this->belongsTo(internet_keluarga::class);
    }

    public function detail_gadget()
    {
        return $this->hasMany(detail_gadget::class);
    }
}
