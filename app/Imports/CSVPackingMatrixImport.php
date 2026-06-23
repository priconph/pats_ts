<?php

namespace App\Imports;

use App\Model\Device;
use Maatwebsite\Excel\Concerns\ToModel;
use Auth;

class CSVPackingMatrixImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Device([
            'name' => $row[1],
            'mrp_no' => $row[2],
            'boxing' => $row[3],
            'barcode' => $row[0],
            'ship_boxing' => $row[4],
            // 'status' => 1,
            // 'created_by' => Auth::user()->id,
            // 'last_updated_by' => Auth::user()->id,
            // 'update_version' => 1,
            // 'updated_at' => date('Y-m-d H:i:s'),
            // 'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
