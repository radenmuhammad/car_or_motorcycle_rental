<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use ZipArchive;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportItem implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
		$items = (array)DB::table('items')
			->where('vehicle_license_plate', $row[0])->first();
		if(empty($items)){
			return new Item([
			   'vehicle_license_plate' => $row[0],
			   'name_of_items' => $row[1],
			   'price' => $row[2],
			   'distributor' => $row[3]
			]);			
		}else{
			DB::table('items')
				->where('vehicle_license_plate', $row[0])			
				->update(array(
					'name_of_items' => $row[1],		
					'price'  => $row[2],	
					'distributor'  => $row[3],
					'updated_at' => Carbon::now()->timezone('Asia/Jakarta')		
			));							
		}    
	}
}
