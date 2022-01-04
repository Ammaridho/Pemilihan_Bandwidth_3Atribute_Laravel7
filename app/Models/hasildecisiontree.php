<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hasildecisiontree extends Model
{
    protected $table = 'hasilDecisiontree';

    public function best_provider()
    {
        return $this->hasMany(best_provider::class);
    }
}
