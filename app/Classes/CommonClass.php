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
			return $db_columns = DB::select("
				select d.description AS COLUMN_COMMENT
				from information_schema.columns c
				inner join pg_class c1
				on c.table_name=c1.relname
				inner join pg_catalog.pg_namespace n
				on c.table_schema=n.nspname
				and c1.relnamespace=n.oid
				left join pg_catalog.pg_description d
				on d.objsubid=c.ordinal_position
				and d.objoid=c1.oid
				where c.table_name='renteds'
				and c.column_name='$column'
			")[0]->column_comment;
			/*
			return $db_columns = DB::select("
			SELECT COLUMN_COMMENT 
			FROM INFORMATION_SCHEMA.COLUMNS 
			WHERE  TABLE_SCHEMA = 'rental' 
			AND TABLE_NAME = 'renteds' 
			AND COLUMN_NAME = '$column'")[0]->COLUMN_COMMENT;			
			*/
		}
	}
?>