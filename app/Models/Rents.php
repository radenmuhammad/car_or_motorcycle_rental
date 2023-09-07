<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Rents extends Model
{
    use HasFactory;
	
	public static function getRentsDataOnlyTen($firstItems=0, $sizeOfPage=10){
		return DB::table('rents')->skip($firstItems)->take($sizeOfPage)->get();
	}	

	public static function countRentsPage($sizeOfPage=10){
		return intval(DB::table('rents')->count()/$sizeOfPage);		
	}
	
	public static function updateRents($requests){			
		$items = DB::table('rents')
			->where('name_of_items', $requests['old_name_of_items'])->get();				
		if(empty($items)){					
			DB::table('rents')->insert(
				array(
					'name_of_items' => $requests['name_of_items'],	
					'type_of_items' => $requests['type_of_items'],
					'days_price' => $requests['days_price'],
					'weeks_price' => $requests['weeks_price'],
					'months_price' => $requests['months_price'],	
					'years_price' => $requests['years_price'],
					'created_at' => Carbon::now()->timezone('Asia/Jakarta')
				)
			);
		}else{
			DB::table('rents')
				->where('name_of_items',$requests['old_name_of_items'])
				->update(array(
						'name_of_items' => $requests['name_of_items'],
						'type_of_items' => $requests['type_of_items'],
						'days_price' => $requests['days_price'],
						'weeks_price' => $requests['weeks_price'],
						'months_price' => $requests['months_price'],	
						'years_price' => $requests['years_price'],
						'updated_at' => Carbon::now()->timezone('Asia/Jakarta')
					));					
		}
	}
		
	public static function getRentsSelected($id_rents){
		return (array)DB::table('rents')->where('name_of_items', $id_rents)->first();
	}	
				
}
