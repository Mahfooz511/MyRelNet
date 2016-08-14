<?php namespace App\Http\Composers;

use Illuminate\Contracts\View\View ;


use App\familyaccesse ;
use App\Family;
use App\Userpreferrence ;

/**
* 
*/
class NavigationComposer {
	

	public function compose(View $view) {

		$userpref = array() ;
		$userpref["lang"] = "Default" ;
		if(Userpreferrence::where('user_id','=',\Auth::id())->exists()){
			$userpref["lang"] = Userpreferrence::where('user_id','=',\Auth::id())->get()[0]->lang ;
		}

		$families = familyaccesse::where('user_id','=',\Auth::id())->get(['family_id','aceess_type']);
		foreach ($families as $key => $value) {
			$famid = $value->family_id ;
			
			if($value->aceess_type == 'view'){
				$userpref["access"][$famid]["viewonly"] = True ;
			}else {
				$userpref["access"][$famid]["viewonly"] = False ;
			}
		}

		// user[access][$famid][viewonly] = true
		// user[lang] = Hindi
		$view->with('userpref', $userpref);

	}

	public function faminfocompose(View $view){
		$faminfo = array() ;

		$familydata = Family::getto("Show")->get(['id','name']) ;
		foreach ($familydata as $key => $value) {
			$faminfo[$value->id] = $value->name ;
		}

		$view->with('faminfo', $faminfo);

	}

	

} //class

?>