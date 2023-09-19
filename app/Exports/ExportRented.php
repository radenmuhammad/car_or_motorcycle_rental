<?php

namespace App\Exports;

use App\Models\Rented;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ExportRented implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Rented::all();
    }

    public function headings(): array
    {
		return Schema::getColumnListing('renteds');
    }	
	
}
