<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class Order extends Model
{
    use HasFactory;
	
	public static function getOrderChart($value){
		return Order::whereRaw("DATE_FORMAT(date_rent_start, '%Y%m')='".$value."'")->count();	
	}
	
	public static function getOrdersDataOnlyTen($searching_orders,$firstItems=0, $sizeOfPage=10){
		$columns = Schema::getColumnListing('orders');
		$query = Order::query();		
		foreach($columns as $column){
			$query->orWhere($column, 'LIKE', '%' . $searching_orders . '%');
		}
		return $query->skip($firstItems*$sizeOfPage)->take($sizeOfPage)->orderBy('created_at','ASC')->get()->toArray();
	}	

	public static function countOrdersPage($searching_orders,$sizeOfPage=10){
		$columns = Schema::getColumnListing('orders');
		$query = Order::query();		
		foreach($columns as $column){
			$query->orWhere($column, 'LIKE', '%' . $searching_orders . '%');
		}		
		$total=count($query->get()->toArray());
		return ceil($total/$sizeOfPage);			
	}

	public static function updateOrders($requests){
		DB::table('orders')
		->where('id', $requests['order_id'])					
		->update(
			array(
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

	// updateOrders
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

	public static function getOrdersSelected($id_order){
		return (array)DB::table('orders')
		->leftJoin('items', 'orders.vehicle_license_plate', '=', 'items.vehicle_license_plate')
		->where('id', $id_order)
		->first();		
	}

	public static function deleteOrdersSelected($id_order){
		DB::table('orders')
		->where('id', $id_order)
		->delete();		
	}

}
