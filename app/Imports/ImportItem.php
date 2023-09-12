<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use ZipArchive;

class ImportItem implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Item([
           'vehicle_license_plate' => $row[0],
           'name_of_items' => $row[1],
           'price' => $row[2],
           'distributor' => $row[3]
        ]);
    }
}
