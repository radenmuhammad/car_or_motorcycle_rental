<?php

namespace App\Imports;

use App\Models\Rented;
use Maatwebsite\Excel\Concerns\ToModel;
use ZipArchive;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportRent implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
		$rents = (array)DB::table('renteds')
			->where('name_of_items', $row[0])->first();
		if(empty($rents)){	
			return new Rented([
				'name_of_items' => $row[0],
				'type_of_items' => $row[1],	
				'days_price' => $row[2],	
				'weeks_price' => $row[3],	
				'months_price' => $row[4],	
				'years_price' => $row[5]		
			]);		
		}else{
			DB::table('renteds')
				->where('name_of_items',$row[0])
				->update(array(
						'type_of_items' => $row[1],
						'days_price' => $row[2],
						'weeks_price' => $row[3],
						'months_price' => $row[4],	
						'years_price' => $row[5],
						'updated_at' => Carbon::now()->timezone('Asia/Jakarta')
					));		
			return null;					
		}					
    }
}
