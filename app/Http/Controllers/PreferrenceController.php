<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Userpreferrence ;

use Illuminate\Http\Request;

class PreferrenceController extends Controller {

	public function lang(){
		$lang = \Input::get('lang');

		if(Userpreferrence::where('user_id','=',\Auth::id())->exists()){
			$pref =  Userpreferrence::where('user_id','=',\Auth::id())->get()[0] ;
			$pref->lang = $lang ;
			$pref->save() ;
		}else {
			$pref = new Userpreferrence ;
			$pref->user_id = \Auth::id() ;
			$pref->lang = $lang ;
			$pref->save() ;
		}
	}
}

