<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class best_provider extends Model
{
    protected $table = 'best_provider';

    public $timestamps = false;

    public function hasildecisiontree()
    {
        return $this->belongsTo(hasildecisiontree::class);
    }
}
