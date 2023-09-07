<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    use HasFactory;
	
	public static function getUsersDataOnlyTen($firstItems=0, $sizeOfPage=10){
		return DB::table('users')->skip($firstItems)->take($sizeOfPage)->get();
	}	

	public static function countUsersPage($sizeOfPage=10){
		return intval(DB::table('users')->count()/$sizeOfPage);		
	}
	
	
}
