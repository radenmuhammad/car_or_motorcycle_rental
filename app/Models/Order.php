<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;
	
	public static function getOrdersDataOnlyTen($firstItems=0, $sizeOfPage=10){
		return DB::table('orders')->skip($firstItems)->take($sizeOfPage)->get();
	}	

	public static function countOrdersPage($sizeOfPage=10){
		return intval(DB::table('orders')->count()/$sizeOfPage);		
	}
	
	public static function insertOrders($requests){
		DB::table('orders')->insert(
			array(
				'vehicle_license_plate' => $requests['vehicle_license_plate'],
				'date_rent_start' => $requests['date_rent_start'],
				'date_rent_end' => $requests['date_rent_end'],
				'address_buyer' => $requests['address_buyer'],
				'address_name' => $requests['address_name'],
				'address_phone' => $requests['address_phone'],
				'years_order' => $requests['years_order'],
				'months_order' => $requests['months_order'],
				'weeks_order' => $requests['weeks_order'],
				'days_order' => $requests['days_order'],	
				'total_of_order'=> $requests['total_of_order'],
				'created_at' => Carbon::now()->timezone('Asia/Jakarta')
			)
		);			
	}	
	
}
