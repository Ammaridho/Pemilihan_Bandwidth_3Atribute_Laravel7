<?php

namespace App\Imports;

use App\Models\detail_gadget;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class last_sheet_detail_gadget_import implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new detail_gadget([
            'id'            => $row['id'],
            'data_penghuni_id'  => $row['data_penghuni_id'],
            'namaGadget'        => $row['namagadget'],
            'range'             => $row['range']
        ]);
    }
}
