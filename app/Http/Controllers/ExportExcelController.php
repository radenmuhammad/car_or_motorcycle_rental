<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExportOrder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportItem;
use App\Imports\ImportRent;



class ExportExcelController extends Controller
{
     public function index()
    {
       return view('index');
    }

    public function export() 
    {
        return Excel::download(new ExportOrder, 'orders.xlsx');
    }    
		 
    public function importItem(Request $request){
        Excel::import(new ImportItem,
                      $request->file('file')->store('files'));
        return redirect()->back();
    }		

    public function importRent(Request $request){
        Excel::import(new ImportRent,
                      $request->file('file')->store('files'));
        return redirect()->back();
    }		
		
}