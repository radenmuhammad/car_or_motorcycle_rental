<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Item extends Model
{
    use HasFactory;

	protected $fillable = ['vehicle_license_plate','name_of_items','price','distributor'];	
		
	public static function getItemsDataOnlyTen($firstItems=0, $sizeOfPage=10){
		return DB::table('items')->skip($firstItems*$sizeOfPage)->take($sizeOfPage)->orderBy('created_at', 'asc')->get();
	}	
	
	public static function countItemsPage($sizeOfPage=10){
		return ceil(DB::table('items')->count()/$sizeOfPage);		
	}
	
	public static function insertItems($requests){
			$items = (array)DB::table('items')
				->where('vehicle_license_plate', $requests['vehicle_license_plate'])->first();				
			if(empty($items)){
				DB::table('items')->insert(
					array(
				'vehicle_license_plate' => $requests['vehicle_license_plate'],	
				'name_of_items' => $requests['name_of_items'],		
				'price'  => $requests['price'],	
				'distributor'  => $requests['distributor'],
				'created_at' => Carbon::now()->timezone('Asia/Jakarta')		
					)
				);									
			}else{
				DB::table('items')
					->where('vehicle_license_plate', $requests['vehicle_license_plate'])			
					->update(array(
				'name_of_items' => $requests['name_of_items'],		
				'price'  => $requests['price'],	
				'distributor'  => $requests['distributor'],
				'updated_at' => Carbon::now()->timezone('Asia/Jakarta')		
				));				
			}	
	}
	
	public static function getItemsByNameOfItemsAndAvailableTrue($name_of_items) {
		return DB::table('items')
				->where('available', true)
				->where('name_of_items', $name_of_items)
				->value('vehicle_license_plate');
	}
	
	public static function updateAvailableFalseFromItemsByVehicleLicensePlate($vehicle_license_plate) {
		DB::table('items')
			->where('vehicle_license_plate', $vehicle_license_plate)
			->update(['available' => false]);	
	}	

	public static function updateAvailableTrueFromItemsByVehicleLicensePlate($vehicle_license_plate) {
            DB::table('items')
                ->where('vehicle_license_plate', $vehicle_license_plate)
                ->update(['available' => true]);	
	}

	public static function getItemsSelected($vehicle_license_plate ){
		return (array)DB::table('items')->where('vehicle_license_plate', $vehicle_license_plate)->first();
	}		
	
}
