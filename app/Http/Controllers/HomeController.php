<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Classes;
use Illuminate\Support\Facades\Schema;
use App\Models\Items;
use App\Models\Users;
use App\Models\Rents;
use App\Models\Orders;

class HomeController extends Controller
{
	
    public function logout(Request $request){
		if($request->session()->has('userName')){
			$request->session()->forget('userName');
		}
        return redirect('/');
    }
	
	public function index(Request $request){
		$requests = $request->only(
			'count_users',
			'count_items',
			'count_rents',
			'count_orders',
			'edit_rents',
			'edit_items'
		);
		$sizeOfPage = 10;
		$requests['count_users']=(empty($requests['count_users'])?0:$requests['count_users'])-1;
		$requests['count_items']=(empty($requests['count_items'])?0:$requests['count_items'])-1;
		$requests['count_rents']=(empty($requests['count_rents'])?0:$requests['count_rents'])-1;
		$requests['count_orders']=(empty($requests['count_orders'])?0:$requests['count_orders'])-1;
		$rent_selected = Array();
		if(empty($requests['edit_rents'])){
			$requests['edit_rents'] = '';
		}else{
			$rent_selected = Rents::getRentsSelected($requests['edit_rents']);				
		}
		$edit_items_selected = Array();
		if(empty($requests['edit_items'])){
			$requests['edit_items'] = '';
		}else{			
			$edit_items_selected = Items::getItemsSelected($requests['edit_items']);		
		}
		if(!Session::has('userName')){
			return Redirect::intended('/');						
		}
		$users = Users::getUsersDataOnlyTen($requests['count_users'], $sizeOfPage);		
		$count_users = Users::countUsersPage($sizeOfPage);						
		$items = Items::getItemsDataOnlyTen($requests['count_items'], $sizeOfPage);
		$count_items = Items::countItemsPage($sizeOfPage);
		$rents = Rents::getRentsDataOnlyTen($requests['count_rents'], $sizeOfPage);
		$count_rents = Rents::countRentsPage($sizeOfPage);		
		$orders = Orders::getOrdersDataOnlyTen($requests['count_orders'], $sizeOfPage);
		$count_orders = Orders::countOrdersPage($sizeOfPage);
        return view('home',['current_orders'=>$requests['count_orders'],
		                    'current_rents'=>$requests['count_rents'],
							'current_items'=>$requests['count_items'],
						    'edit_items_selected'=>$edit_items_selected,
							'rent_selected'=>$rent_selected,
							'orders' => $orders,
							'count_orders' => $count_orders,
		                    'rents' => $rents,
							'count_rents' => $count_rents,							
							'items' => $items,
							'count_items' => $count_items,							
		                    'users' => $users,
		                    'count_users' => $count_users,						
							'userName' => Session::get('userName')]);
    }
	
	public function update_the_returned_items(Request $request){
		$requests = $request->only('vehicle_license_plate');
		Items::updateAvailableTrueFromItemsByVehicleLicensePlate($requests['vehicle_license_plate']);				
		return Redirect::intended('home');			
	}
	
	public function update_items(Request $request){
		$requests = $request->only(
		'vehicle_license_plate',	
		'name_of_items',		
		'price',	
		'distributor',
		'created_at'		
		);
		Items::insertItems($requests);		
		return Redirect::intended('home');					
	}
	
	public function update_rent(Request $request){
		$requests = $request->only(
			'old_name_of_items',
			'name_of_items',
			'type_of_items',
			'days_price',
			'weeks_price',
			'months_price',
			'years_price'
		);		
		Rents::updateRents($requests);
		return Redirect::intended('home');					
	}
	
	public function update_orders(Request $request){
		$requests = $request->only('name_of_items',
								   'date_rent_start', 
								   'date_rent_end', 
								   'address_buyer', 
								   'address_name', 
								   'address_phone',
								   'days_price',
								   'weeks_price',
								   'months_price',
								   'years_price');			
		// Declare and define two dates
		$date1 = strtotime($requests['date_rent_start']);
		$date2 = strtotime($requests['date_rent_end']);

		// Formulate the Difference between two dates
		$diff = abs($date2 - $date1);

		// To get the year divide the resultant date into
		// total seconds in a year (365*60*60*24)
		$years_order = 0;
		if($requests['years_price'] > 0){
			$years_order = floor($diff / (365*60*60*24));			
		}

		// To get the month, subtract it with years and
		// divide the resultant date into
		// total seconds in a month (30*60*60*24)
		$months_order = 0;
		if($requests['months_price'] > 0){
			$months_order = floor(($diff - $years_order * 365*60*60*24)
										/ (30*60*60*24));
		}
		// To get the day, subtract it with years and
		// months and divide the resultant date into
		// total seconds in a days (60*60*24)
		$days_order = floor(($diff - $years_order * 365*60*60*24 -
					$months_order*30*60*60*24)/ (60*60*24));
		// Print the result
		$weeks_order = 0;
		if($requests['weeks_price'] > 0){
			$weeks_order = floor($days_order / 7);			
		}
		$days_order = $days_order - ($weeks_order * 7);
		
		$total_of_order = ($years_order * $requests['years_price']) +
					   ($months_order * $requests['months_price']) +	
					   ($weeks_order * $requests['weeks_price']) +	
					   ($days_order * $requests['days_price'])					   
		;
		$vehicle_license_plate = Items::getItemsByNameOfItemsAndAvailableTrue($requests['name_of_items']);
		Items::updateAvailableFalseFromItemsByVehicleLicensePlate($vehicle_license_plate);		
		if(!empty($vehicle_license_plate)){
			$requests['vehicle_license_plate'] = $vehicle_license_plate;
			$requests['days_order'] = $days_order;
			$requests['months_order'] = $months_order;
			$requests['weeks_order'] = $weeks_order;
			$requests['years_order'] = $years_order;
			$requests['total_of_order'] = $total_of_order;			
			Orders::insertOrders($requests);	
			return view("update_orders",["alert"=>"this item has been already rent!"]);					
		}else{
			return view("update_orders",["alert"=>"this item is not available!"]);					
		}
	}
	
}
