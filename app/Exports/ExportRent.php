<?php

namespace App\Exports;

use App\Models\Rent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ExportRent implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Rent::all();
    }

    public function headings(): array
    {
		return Schema::getColumnListing('rents');
    }	
	
}
