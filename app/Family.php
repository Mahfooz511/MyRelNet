<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FamilyForUserTrait;
use App\Person;
use App\familyaccesse;

class Family extends Model {

	 use SoftDeletes;

	//use FamilyForUserTrait ;
	protected $table = 'families';

	protected $fillable = [
		'name'
	];

	public function persons() {
		return $this->hasMany('App\Person');
	}

	public function scopegetto($query, $purpose = "Show"){
	//Arguments:  Show, Rename, Delete, Split, Join, Share, ChangeAccess, SetRoot
		if($purpose == "Show" || $purpose == "SetRoot"){
			$families = familyaccesse::where('user_id','=',\Auth::id() )
								->whereIn('aceess_type',['owner','edit','view'])
								->get(['family_id']) ;
		}
		elseif (in_array($purpose, ["Rename", "Join", "Share", "ChangeAccess", "Edit","Copy"])) {
			$families = familyaccesse::where('user_id','=',\Auth::id() )
								->whereIn('aceess_type',['owner','edit'])
								->get(['family_id']) ;
			
		}elseif (in_array($purpose, ["Delete", "Split"])) {
			$families = familyaccesse::where('user_id','=',\Auth::id() )
								->where('aceess_type','=','owner')
								->get(['family_id']) ;
		} 
		else return -1 ;

		$searcharray = array() ;
		foreach ($families as $key => $value) {
			$searcharray[$key] = $value->family_id ;
		}
		//return Family::whereIn('id',$searcharray)->get();
        return $query->whereIn('id',$searcharray);
 
	}

}
