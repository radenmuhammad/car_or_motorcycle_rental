<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;
	
	protected $fillable = ['name','username','email','password','role'];	
	
	public static function getUsersDataOnlyTen($firstItems=0, $sizeOfPage=10){
		return DB::table('users')->skip($firstItems)->take($sizeOfPage)->get();
	}	

	public static function countUsersPage($sizeOfPage=10){
		return intval(DB::table('users')->count()/$sizeOfPage);		
	}
	
	
}