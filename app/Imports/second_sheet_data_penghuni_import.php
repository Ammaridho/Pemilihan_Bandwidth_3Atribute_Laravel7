<?php

namespace App\Imports;

use App\Models\data_penghuni;
use Maatwebsite\Excel\Concerns\ToModel;

class second_sheet_data_penghuni_import implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new data_penghuni([
            'internet_keluarga_id'  => $row[1],
            'nama'                  => $row[2],
            'banyakGadget'          => $row[3]
        ]);
    }
}
