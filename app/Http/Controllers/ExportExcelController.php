<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExportUsers;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportItem;



class ExportExcelController extends Controller
{
     public function index()
    {
       return view('index');
    }

    public function export() 
    {
        return Excel::download(new ExportUsers, 'orders.xlsx');
    }    
		
	public function importView(Request $request){
        return view('importFile');
    }
 
    public function import(Request $request){
        Excel::import(new ImportItem,
                      $request->file('file')->store('files'));
        return redirect()->back();
    }		
		
}