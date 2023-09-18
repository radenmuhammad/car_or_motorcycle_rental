<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class Rent extends Model
{
	protected $fillable = [
	'name_of_items','type_of_items','days_price','weeks_price','months_price','years_price'
	];	
	
    use HasFactory;
	
	public static function getRentsDataOnlyTen($searching_rents,$firstItems=0, $sizeOfPage=10){
		$columns = Schema::getColumnListing('rents');
		$query = Rent::query();		
		foreach($columns as $column){
			$query->orWhere($column, 'LIKE', '%' . $searching_rents . '%');
		}
		return $query->skip($firstItems*$sizeOfPage)->take($sizeOfPage)->orderBy('created_at', 'asc')->get()->toArray();
	}	

	public static function countRentsPage($searching_rents,$sizeOfPage=10){
		$columns = Schema::getColumnListing('rents');
		$query = Rent::query();		
		foreach($columns as $column){
			$query->orWhere($column, 'LIKE', '%' . $searching_rents . '%');
		}		
		$total=count($query->get()->toArray());		
		return ceil($total/$sizeOfPage);		
	}
	
	public static function updateRents($requests){			
		$items = (array)DB::table('rents')
			->where('name_of_items', $requests['old_name_of_items'])->first();
		if(empty($requests['old_name_of_items'])){
		$items = (array)DB::table('rents')
			->where('name_of_items', $requests['name_of_items'])->first();			
		}			
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

	public static function getDeletedRentsSelected($id_rents){
		return (array)DB::table('rents')->where('name_of_items', $id_rents)->delete();
	}	
				
}
