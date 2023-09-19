<?php
	namespace App\Classes;
	use Illuminate\Support\Facades\DB;	
	
	class CommonClass {
		public function __construct() {
			return "construct function was initialized.";
		}
		function rupiah($angka){
			
			$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
			return $hasil_rupiah;
		 
		}
		
		function getRentedsTableComment($column){
			return $db_columns = DB::select("SELECT COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE  TABLE_SCHEMA = 'rental' AND TABLE_NAME = 'renteds' AND COLUMN_NAME = '$column'")[0]->COLUMN_COMMENT;			
		}
	}
?>