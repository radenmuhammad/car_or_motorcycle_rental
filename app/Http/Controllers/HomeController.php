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
use App\Models\Item;
use App\Models\User;
use App\Models\Rented;
use App\Models\Order;
use App\Charts\UserChart;
use PDF;

class HomeController extends Controller
{
	
	public function create_orders_pdf() {
      // retreive all records from db
      $orders = Order::all()->toArray();
      // share data to view
      view()->share('orders',$orders);
      $pdf = PDF::loadView('pdf_view_for_order', $orders)->setPaper('a4', 'landscape');;
      // download PDF file with download method
      return $pdf->download('orders.pdf');
    }
	
	public function create_items_pdf(){
		  // retreive all records from db
		  $items = Item::all()->toArray();
		  // share data to view
		  view()->share('items',$items);
		  $pdf = PDF::loadView('pdf_view_for_item', $items)->setPaper('a4', 'landscape');;
		  // download PDF file with download method
		  return $pdf->download('items.pdf');		
	}

	public function create_renteds_pdf(){
		  // retreive all records from db
		  $renteds = Rented::all()->toArray();
		  // share data to view
		  view()->share('renteds',$renteds);
		  $pdf = PDF::loadView('pdf_view_for_rented', $renteds)->setPaper('a4', 'landscape');;
		  // download PDF file with download method
		  return $pdf->download('renteds.pdf');		
	}
	
    public function logout(Request $request){
		if($request->session()->has('userName')){
			$request->session()->forget('userName');
		}
        return redirect('actionlogout');
    }
	
	public function index(Request $request){
		$requests = $request->only(
			'count_users',
			'count_items',
			'count_renteds',
			'count_orders',
			'edit_renteds',
			'edit_items',
			'delete_items',
			'delete_renteds',
			'searching_items',
			'searching_orders',
			'searching_renteds'
		);
		$sizeOfPage = 5;
		$requests['count_users']=(empty($requests['count_users'])?0:$requests['count_users'])-1;
		$requests['count_items']=(empty($requests['count_items'])?0:$requests['count_items'])-1;
		$requests['count_renteds']=(empty($requests['count_renteds'])?0:$requests['count_renteds'])-1;
		$requests['count_orders']=(empty($requests['count_orders'])?0:$requests['count_orders'])-1;
		$requests['searching_items']=(empty($requests['searching_items'])?'':$requests['searching_items']);
		$requests['searching_orders']=(empty($requests['searching_orders'])?'':$requests['searching_orders']);
		$requests['searching_renteds']=(empty($requests['searching_renteds'])?'':$requests['searching_renteds']);
		$edit_items_selected = Array();
		if(empty($requests['delete_items'])){
			$requests['delete_items'] = '';
		}else{			
			Item::deleteItemsSelected($requests['delete_items']);		
			return Redirect::intended('home');		
		}
		$rent_selected = Array();
		if(empty($requests['edit_renteds'])){
			$requests['edit_renteds'] = '';
		}else{
			$rent_selected = Rented::getRentedsSelected($requests['edit_renteds']);				
		}
		if(empty($requests['delete_renteds'])){
			$requests['delete_renteds'] = '';
		}else{
			Rented::getDeletedRentedsSelected($requests['delete_renteds']);				
			return Redirect::intended('home');		
		}		
		$edit_items_selected = Array();
		if(empty($requests['edit_items'])){
			$requests['edit_items'] = '';
		}else{			
			$edit_items_selected = Item::getItemsSelected($requests['edit_items']);	
		}
		$users = User::getUsersDataOnlyTen($requests['count_users'], $sizeOfPage);		
		$count_users = User::countUsersPage($sizeOfPage);						
		$items = Item::getItemsDataOnlyTen($requests['searching_items'],$requests['count_items'], $sizeOfPage);
		$count_items = Item::countItemsPage($requests['searching_items'],$sizeOfPage);
		$renteds = Rented::getRentedsDataOnlyTen($requests['searching_renteds'],$requests['count_renteds'], $sizeOfPage);
		$count_renteds = Rented::countRentedsPage($requests['searching_renteds'],$sizeOfPage);		
		$orders = Order::getOrdersDataOnlyTen($requests['searching_orders'],$requests['count_orders'], $sizeOfPage);
		$count_orders = Order::countOrdersPage($requests['searching_orders'],$sizeOfPage);
		$year = [date("Ym",strtotime("-4 month")),date("Ym",strtotime("-3 month")),date("Ym",strtotime("-2 month")),date("Ym",strtotime("-1 month")),date("Ym")];
        $order_charts = Array();
        foreach ($year as $key => $value) {
            $order_charts[] = Order::getOrderChart($value);
        }        
		$year = [date("M Y",strtotime("-4 month")),date("M Y",strtotime("-3 month")),date("M Y",strtotime("-2 month")),date("M Y",strtotime("-1 month")),date("M Y")];
		return view('home',['searching_renteds'=>$requests['searching_renteds'],
		                    'searching_items'=>$requests['searching_items'],
							'searching_orders'=>$requests['searching_orders'],	
							'year'=>json_encode($year,JSON_NUMERIC_CHECK),
							'order_charts'=>json_encode($order_charts,JSON_NUMERIC_CHECK),
							'current_orders'=>$requests['count_orders'],
		                    'current_renteds'=>$requests['count_renteds'],
							'current_items'=>$requests['count_items'],
						    'edit_items_selected'=>$edit_items_selected,
							'rent_selected'=>$rent_selected,
							'orders' => $orders,
							'count_orders' => $count_orders,
		                    'renteds' => $renteds,
							'count_renteds' => $count_renteds,							
							'items' => $items,
							'count_items' => $count_items,							
		                    'users' => $users,
		                    'count_users' => $count_users,						
							'userName' => Session::get('userName')
							]);
    }
	
	public function calculate_distance_between_two_date(Request $request){
		$requests = $request->only('date_rent_start',
								   'date_rent_end',
								   'years_price',
								   'weeks_price',								   
								   'months_price',
								   'days_price');	
		$date1 = strtotime($requests['date_rent_start']);
		$date2 = strtotime($requests['date_rent_end']);
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
		if($requests['date_rent_end']!="" && strtotime($requests['date_rent_end']) > strtotime($requests['date_rent_start'])){
			return view("show_calculate",[
										   "years_order"=>$years_order,
										   "months_order"=>$months_order,										   
										   "weeks_order"=>$weeks_order,
										   "days_order"=>$days_order,
										   "total_of_order"=>$total_of_order
										  ]);				
		}
	}
		
	public function update_the_returned_items(Request $request){
		$requests = $request->only('vehicle_license_plate');
		Item::updateAvailableTrueFromItemsByVehicleLicensePlate($requests['vehicle_license_plate']);				
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
		Item::insertItems($requests);		
		return Redirect::intended('home');					
	}
	
	public function update_renteds(Request $request){
		$requests = $request->only(
			'old_name_of_items',
			'name_of_items',
			'type_of_items',
			'days_price',
			'weeks_price',
			'months_price',
			'years_price',
			'image'
		);		
		$requests['image'] = time() . '.' . $request->image->extension();		
        $request->image->storeAs('public/images', $requests['image']);
		Rented::updateRenteds($requests);
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
		$date1 = strtotime($requests['date_rent_start']);
		$date2 = strtotime($requests['date_rent_end']);
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
		$vehicle_license_plate = Item::getItemsByNameOfItemsAndAvailableTrue($requests['name_of_items']);
		Item::updateAvailableFalseFromItemsByVehicleLicensePlate($vehicle_license_plate);		
		if(!empty($vehicle_license_plate)){
			$requests['vehicle_license_plate'] = $vehicle_license_plate;
			$requests['days_order'] = $days_order;
			$requests['months_order'] = $months_order;
			$requests['weeks_order'] = $weeks_order;
			$requests['years_order'] = $years_order;
			$requests['total_of_order'] = $total_of_order;			
			Order::insertOrders($requests);	
			return view("update_orders",["alert"=>"this item has been already rent!"]);					
		}else{
			return view("update_orders",["alert"=>"this item is not available!"]);					
		}
	}
		
}
