<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExportOrder;
use App\Exports\ExportItem;
use App\Exports\ExportRented;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportItem;
use App\Imports\ImportRent;



class ExportExcelController extends Controller
{
     public function index()
    {
       return view('index');
    }

    public function exportItems() 
    {
        return Excel::download(new ExportItem, 'items.xlsx');
    }    

    public function exportRenteds() 
    {
        return Excel::download(new ExportRented, 'renteds.xlsx');
    }    

    public function exportOrders() 
    {
        return Excel::download(new ExportOrder, 'orders.xlsx');
    }    
		 
    public function importItems(Request $request){
        Excel::import(new ImportItem,
                      $request->file('file')->store('files'));
        return redirect()->back();
    }		

    public function importRents(Request $request){
        Excel::import(new ImportRent,
                      $request->file('file')->store('files'));
        return redirect()->back();
    }		
		
}