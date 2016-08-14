<?php namespace RelNet ;

use App\familyaccesse ;
use App\Family;
use App\Person;
use App\Userpreferrence ;
use RelNet\RelationMetaData;
use Exception;


class Misc {

	// Check if user has proper access to a family
	// 
	public static function isfam_valid($famid,$purpose = "Show"){
		$families_rows = Family::getto($purpose)->get(['id']) ;
		foreach ($families_rows as $key => $value) {
			$families[$key] = $value->id ;
		}
		if(! in_array($famid, $families)){
			throw new Exception('No proper access to this family');
		}
		return True ;
	}

	// Takes person id and checks if user is authenticated to see this ID
	// Return - True/False 
	public static function isid_valid($pid,$purpose = "Show"){
		$family = Person::where('id','=',$pid)->get(['family_id']);
		if($family->count() == 0 ){
			throw new Exception('No such person');	
		}
		return Misc::isfam_valid($family[0]->family_id,$purpose);
	}

	// get access type for family - owner/edit/view
	public static function get_accesstype($famid){
		return familyaccesse::where('family_id','=',$famid)->where('user_id','=',\Auth::id())->get(['aceess_type'])[0]->aceess_type ;
	}

	public static function get_lang(){
		$userlang = Userpreferrence::where('user_id','=',\Auth::id())->get(['lang']) ;
		if($userlang->isEmpty()) { return 'English';}
		return $userlang[0]->lang ;
	}

	public static function get_relationarray(){
		$userlang = Misc::get_lang();
		
		$staticdata = new RelationMetaData() ;
		$relationarray = array("Maan" 	=> $staticdata->get_lang_name("Maan", $userlang),
						  	   "Pita"	=> $staticdata->get_lang_name("Pita", $userlang),
						  	   "Bhai"	=> $staticdata->get_lang_name("Bhai", $userlang),
						  	   "Behan"	=> $staticdata->get_lang_name("Behan", $userlang),
						  	   "Beta"	=> $staticdata->get_lang_name("Beta", $userlang),
						  	   "Beti"	=> $staticdata->get_lang_name("Beti", $userlang),
						  	   "Pati"	=> $staticdata->get_lang_name("Pati", $userlang),
						  	   "Patni"	=> $staticdata->get_lang_name("Patni", $userlang));
		return $relationarray ;
	}

	public static function get_relation_in_mylang($relation){
		$userlang = Misc::get_lang();
		$staticdata = new RelationMetaData() ;

		$myrel = $staticdata->get_lang_name($relation, $userlang) ; 

		return $myrel ;
	}

	public static function get_revrelation_in_mylang($relation,$gender){
		$staticdata = new RelationMetaData() ;
		$userlang = Misc::get_lang();
		$myrel = $staticdata->get_rev_relation($relation, $gender) ; 
		$myrel = $staticdata->get_lang_name($relation, $userlang) ; 
		return $myrel ;	
	}



} //class

?>