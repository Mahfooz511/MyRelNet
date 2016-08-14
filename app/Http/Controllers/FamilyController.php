<?php namespace App\Http\Controllers;

use App\Http\Requests\CreateFamilyRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\familyaccesse ;
use App\Http\Requests;
use RelNet\FamilyGraph;
use RelNet\Misc;

use App\Family;
use App\Person;
use App\Relation;


class FamilyController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getmembers(){
		$famid = \Input::get('famid');
		
 	 	Misc::isfam_valid($famid,$purpose = "Edit");

 	 	$members = Person::where('family_id','=',$famid)->get(['id','name']);
 	 	return \Response::make($members);
	}


	public function create(){
		return view('addfamily') ;
	}

	public function map($famid){
		Misc::isfam_valid($famid,"Show");

		$persons = Person::where('family_id','=',$famid)->get(['id','name','city','state','country']);

		/* To complete sidepan data for filtering
		select count(*) from persons 
where family_id = 2  
and  (`city` is NULL or city = "") and
(state is NULL or state = "") 
and (country is NULL or country = "") ORDER BY `family_id` ASC

*/

		return view('map', compact('persons','famid'));
	}

	

	public function store(CreateFamilyRequest $request){		
		
		$family = new Family($request->all()) ;
		$familyaccess = new familyaccesse() ;
		$family->user_id = \Auth::id() ;
		
		$family->save();

		$familyaccess->family_id = $family->id ;
		$familyaccess->aceess_type = 'owner' ;
		$familyaccess->root_id = -1 ;
		$familyaccess->user_id = \Auth::id() ;

		$familyaccess->save() ;

		return redirect('/');
	}


	// Family Rename
	public function update($id, CreateFamilyRequest $request){
		$fid = $request->family_id ;
		$family = Family::FindOrFail($fid) ;
		$family->name = $request->name ;

		$family->update() ;

		return redirect('/');
	}

	

	public function edit($id){
		$family = Family::FindOrFail($id) ;

		return view('familyedit',compact('family')) ;
	}

	private function get_families($purpose = "Show"){
		$familydata = Family::getto($purpose)->get(['id','name']) ;

		$families = array() ;
		foreach ($familydata as $key => $value) {
			$families[$value->id] = $value->name ;
		}
		return $families ;
	}

	public function rename(){
		$families = $this->get_families("Rename") ;
		return view('familyrename',compact('families')) ;
	}

	public function nameupdate(Request $request){
		$fid = $request->family_id ;
		$family = Family::getto("Rename")->FindOrFail($fid) ;
		$family->name = $request->name ;

		$family->update() ;

		return redirect('/');
	}

	public function delete(){
		$families = $this->get_families("Delete") ;
		return view('familydelete',compact('families')) ;
	}

	public function remove(Request $request){
		$fid = $request->family_id ;
		
		//$accesstype = familyaccesse::where('family_id','=',$fid)->where('user_id','=',\Auth::id())->get(['aceess_type'])[0]->aceess_type ;
		$accesstype = Misc::get_accesstype($fid) ;
		if($accesstype == 'owner'){
			// Delete all entries from tables for all users for this Family
			$affectedRows1 = familyaccesse::where('family_id','=',$fid)->delete();
			$affectedRows2 = Family::where('id','=',$fid)->delete();
			$affectedRows3 = Person::where('family_id','=',$fid)->delete();
			$affectedRows4 = Relation::where('family_id','=',$fid)->delete();
		}elseif($accesstype == 'edit' || $accesstype == 'view'){
			// Delete all entries from access table for this user
			$affectedRows = familyaccesse::where('family_id','=',$fid)->where('user_id','=',\Auth::id)->delete();

		}else{
			//throw exception
			abort(403, 'Unauthorized action.');
		}

		return redirect('/');
	}

	public function show($famid) {	 	

 	 	foreach (Family::getto("Show")->get(['id']) as $key => $value) {
 	 		$famarray[$key] = $value->id ;
 	 	}
 	 	if(! in_array($famid, $famarray )){
 	 		return redirect('/') ;
 	 	}

 	 	$famgraph = new FamilyGraph($famid); 

 	 	// check if member count is 0
 	 	if($famgraph->get_member_count() <= 0){
			return \Redirect::to("family/$famid/person/add")->withInput()->withErrors(['No Member in the family. Please add members.']);
 	 	}
 	 	
 	 	// If member find link is hit
 	 	$person = null ;
 	 	if (\Input::has('p'))
		{
		    $person = \Input::get('p');
		}
		// if relation find link is hit
		$rfid1 = null ;
		$rfid2 = null ;
		if(\Input::has('rfid1') && \Input::has('rfid2') )
		{
			$rfid1 = \Input::get('rfid1'); 
			$rfid2 = \Input::get('rfid2'); 
		}

 	 	//$accesstype = familyaccesse::where('family_id','=',$famid)->where('user_id','=',\Auth::id())->get(['aceess_type'])[0]->aceess_type ;
 	 	$accesstype = Misc::get_accesstype($famid);
 	 	$viewonly  = False  ;
 	 	if($accesstype == 'view'){
 	 		$viewonly = True ;
 	 	}

 	 	$global_vars = [] ;
 		//$global_vars['p1'] = 23 ;
 		//$global_vars['p2'] = 29 ;
 		//$global_vars['edge'] = '#23_29' ;
 		//$global_vars['relation'] = $testclass->FindRelationsBetween($global_vars['p1'] , $global_vars['p2'] );
//dd($famgraph);
 	 	// Get root
 	 	$root = familyaccesse::where('family_id','=',$famid)->where('user_id','=',\Auth::id())->get(['root_id'])[0]->root_id;
 	 	//dd($root);
 	 	if($root == -1 || $root == 0){
 	 		$root = $famgraph->root;
 	 	}

		return view('familyhome', ['cyobject' => $famgraph->cy_object, 
			                       'root' => $root,
			                     // 'global_vars' => $global_vars
			                       'famid' => $famid,
			                       'viewonly' => $viewonly ,
			                       'person' => $person ,
			                       'rfid1' => $rfid1, 
			                       'rfid2' => $rfid2, 
			                      ]) ;
	}

	public function join() {
		$families = $this->get_families("Join") ;
		$relations = Misc::get_relationarray() ;
		return view('joinfamilies',compact('families','relations')) ;
	}

	public function joinstore(Request $request){
		 $messages = [
    		'required' => 'The :attribute field is required.',
		];
		$v = \Validator::make($request->all(), [
		"firstfamily" => 'required',
		"firstfamilymember" => 'required',
		"relation" => 'required',
		"secondfamily" => 'required',
		"secondfamilymember" => 'required',
		"option" => 'required',
	    ]);

	    if ($v->fails())
	    {
	        return redirect()->back()->withInput()->withErrors($v->errors());
	    }
		if($request->firstfamily ==  $request->secondfamily) {
			return redirect()->back()->withInput()->withErrors(['The first family and second family should be different.']);
		}
	
		Misc::isfam_valid($request->firstfamily,"Join");
		Misc::isfam_valid($request->secondfamily,"Join");


		// if Option = Keep just one family, then cant have Edit access on both Families
		if($request->option == 2){
			$access1 = Misc::get_accesstype($request->firstfamily);
			$access2 = Misc::get_accesstype($request->secondfamily);

			if($access1 == 'Edit' && $access2 == 'Edit'){
				return redirect('family/join')->withInput(); 
			}
		}

		$newfamilyname = "NEW FAMILY";

		// Create New Family and get the ID
		$family = new Family ;
		$family->name = $newfamilyname ;		
		$family->user_id = \Auth::id() ;
		$family->save();

		// Get all the people from first and second family 
		$fields = array('user_id', 'family_id', 'name', 'nickname', 'gender', 'age', 'siblingno', 'image', 'location', 'deadoralive', 'generation', 'facebookid', 'googleid', 'email_id', 'description');
		$members1 = Person::whereIn('family_id',array($request->firstfamily, $request->secondfamily))->get();
		$personmap = $this->copymembers($family->id,$members1);		

		// Copy all relations from old families to new families
		$relations1 = Relation::whereIn('family_id',array($request->firstfamily,$request->secondfamily))->get() ;
		$this->copyrelations($family->id,$relations1,$personmap) ;
		
		// Add new relation connectiong two families
		$relation = new Relation ;
		$relation->user_id = \Auth::id() ;
		$relation->family_id = $family->id ;
		$relation->person_id = $personmap[$request->firstfamilymember] ;
		$relation->relation  = $request->relation ;
		$relation->relative_id = $personmap[$request->secondfamilymember] ;
		$relation->save() ;

		//1. Keep original families
		//2. Keep just one family
		// if option==1 then no transfer of access rights
		$familyaccess = new familyaccesse ;
		$ff = $request->firstfamily;
		$sf = $request->secondfamily;
		if($request->option == 2){
			$familyaccess1 = familyaccesse::whereIn('family_id',array($request->firstfamily,$request->secondfamily))->get() ;

			foreach ($familyaccess1 as $key => $value) {
				$allaccess[$value->user_id][$value->family_id]['a'] = $value->aceess_type ; 
				$allaccess[$value->user_id][$value->family_id]['r'] = $value->root_id ;
				$allaccess[$value->user_id][$value->family_id]['y'] = $value->your_id ;
			}

			foreach ($allaccess as $uid => $value) {
				if($uid == \Auth::id() ){
					if($allaccess[$uid][$ff]['a'] == 'owner' && $allaccess[$uid][$sf]['a'] == 'owner'){
						$newaccess = 'owner' ;
					}else if(($allaccess[$uid][$ff]['a'] == 'owner' && $allaccess[$uid][$sf]['a'] == 'edit') 
							||($allaccess[$uid][$ff]['a'] == 'edit' && $allaccess[$uid][$sf]['a'] == 'owner')){
						$newaccess = 'edit' ;
					}else if($allaccess[$uid][$ff]['a'] == 'edit' && $allaccess[$uid][$sf]['a'] == 'edit'){
						return redirect('/')->withInput();  
					}
				} else {
					//if user has access to both Families
					if(isset($allaccess[$uid][$ff]['a']) && isset($allaccess[$uid][$sf]['a'])) {
						if($allaccess[$uid][$ff]['a'] == 'owner' || $allaccess[$uid][$sf]['a'] == 'owner'){
							$newaccess = 'owner' ;
						} else if($allaccess[$uid][$ff]['a'] == 'edit' && $allaccess[$uid][$sf]['a'] == 'edit'){
							$newaccess = 'edit' ;
						}else if(($allaccess[$uid][$ff]['a'] == 'edit' && $allaccess[$uid][$sf]['a'] == 'view') 
								||($allaccess[$uid][$ff]['a'] == 'view' && $allaccess[$uid][$sf]['a'] == 'edit')){
							$newaccess = 'edit' ;
						} else{ //ff== view && sf== view
							$newaccess = 'view' ;
						}
					} else if(isset($allaccess[$uid][$ff]['a'])){  //user has access to first family
						$newaccess = $allaccess[$uid][$ff]['a'] ;
					} else if(isset($allaccess[$uid][$sf]['a'])){  //user has access to second family
						$newaccess = $allaccess[$uid][$sf]['a'] ;
					}
				}
				if(isset($allaccess[$uid][$ff]['r']) && $allaccess[$uid][$ff]['r'] >0){
					$familyaccess->root_id = $allaccess[$uid][$ff]['r'] ;
				}
				else if(isset($allaccess[$uid][$sf]['r'])){
					$familyaccess->root_id = $allaccess[$uid][$sf]['r'] ;
				}
				if(isset($allaccess[$uid][$ff]['y']) && $allaccess[$uid][$ff]['y'] != null){
					$familyaccess->your_id = $allaccess[$uid][$ff]['y'] ;
				}
				else {
					$familyaccess->your_id = $allaccess[$uid][$ff]['y'] ;
				}
				$familyaccess->family_id = $family->id ;
				$familyaccess->aceess_type = $newaccess ;
				$familyaccess->root_id = -1 ;
				$familyaccess->user_id = $uid ;	
				$familyaccess->save() ;
			}
			// Logic to delete old families
			//  delete FAMILY, PERSON, familyaccess , relations, image files
			$persons = Family::whereIn('id',array($ff,$sf))->get(['id','family_id','image']) ;
			foreach ($persons as $id) {
				if($id->image != null){
					$imgfile = "user/img/" . $id->id . "f" . $id->family_id . "." . $id->image ;
					\File::move($imgfile,$imgfile . ".DELETE") ;
				}
			}
			Person::whereIn('family_id',array($ff,$sf))->delete();
			Family::whereIn('id',array($ff,$sf))->delete() ; 
			Relation::whereIn('family_id',array($ff,$sf))->delete();
			familyaccesse::whereIn('family_id',array($ff,$sf))->delete() ;
		}
		else{
			// option==1, keep original families
			//set user as owner of new family
			$familyaccess = new familyaccesse ;
			$familyaccess->family_id = $family->id ;
			$familyaccess->aceess_type = 'owner' ;
			$familyaccess->root_id = -1 ;
			$familyaccess->user_id = \Auth::id() ;
			$familyaccess->save() ;
		}
		return redirect('/');
	}

	public function copy(){
		$families = $this->get_families("Copy") ;
		return view('familycopy',compact('families')) ;
	}

	public function copystore(Request $request){
		$fid = $request->family_id ;
		Misc::isfam_valid($fid,"Copy");

		$newfamname = Family::where('id','=',$fid)->get(['name'])[0]->name . "-NEW" ;
		$newfamily = new Family ;
		$newfamily->user_id = \Auth::id();
		$newfamily->name = $newfamname ;
		$newfamily->save();

		// copy persons for new family
		$members = Person::where('family_id','=',$fid)->get();
		$personmap = $this->copymembers($newfamily->id,$members) ;

		// Copy all relations from old families to new families
		$relations = Relation::where('family_id','=',$fid)->get() ;
		$this->copyrelations($newfamily->id,$relations,$personmap) ;

		$famacess = new familyaccesse ;
		$famacess->user_id = \Auth::id() ;
		$famacess->family_id = $newfamily->id ;
		$famacess->aceess_type = 'owner' ;
		$famacess->save() ;

		return redirect('/');
	}

	private function copymembers($newfid,$members){
		$fields = array('user_id', 'family_id', 'name', 'nickname', 'gender', 'age', 'siblingno', 'image', 'location', 'deadoralive', 'generation', 'facebookid', 'googleid', 'email_id', 'description');
		foreach($members as $key => $value) {
			$person = new Person ;
			foreach ($fields as $field) {
				$person->$field = $value->$field ;
			}
			$person->user_id = \Auth::id() ;
			$person->family_id = $newfid ;
			$person->image = $value->image;
			$person->save() ;
			if($value->image != null){
				 // copy file
				$file = "user/img/" . $value->id . "f" . $value->family_id . "." . $value->image ;
				$dest = "user/img/" . $person->id . "f" . $person->family_id . "." . $person->image ; 
				if ( ! \File::copy($file, $dest))
				{
				    die("Couldn't copy file");
				}
			}
			$personmap[$value->id] = $person->id ;
		}
		return $personmap ;
	}

	private function copyrelations($famid,$relations,$personmap){
		foreach ($relations as $key => $value) {
			$relation = new Relation ;
			$relation->relation = $value->relation ;
			$relation->user_id = \Auth::id() ;
			$relation->family_id = $famid ;
			$relation->person_id = $personmap[$value->person_id] ;
			$relation->relative_id = $personmap[$value->relative_id] ;
			$relation->save() ;
		}
	}

	// Split family form
	public function split(){
		$families = $this->get_families("Split") ;
		$relations = Misc::get_relationarray() ;
		return view('splitfamilies',compact('families','relations')) ;
	}

	// Split family - store
	public function splitstore(Request $request){
		$messages = [
    		'required' => 'The :attribute field is required.',
		];
		$v = \Validator::make($request->all(), [
		"splitfamily" => 'required',
		"firstmember" => 'required',
		"secondmember" => 'required',
	    ]);
	    if ($v->fails())
	    {
	        return redirect()->back()->withInput()->withErrors($v->errors());
	    }
		if($request->firstmember ==  $request->secondmember) {
			return redirect()->back()->withInput()->withErrors(['Members should be different.']);
		}
		Misc::isfam_valid($request->splitfamily,"Split");

		//1.Copy family
		$ff = Family::find($request->splitfamily);
		$tempname = $ff->name ;
		$ff->name = $ff->name . "_1" ;
		$ff->save();
		$sf = new Family ;
		$sf->user_id = \Auth::id();
		$sf->name = $tempname . "_2" ;
		$sf->save();

		$ffmembers = Person::where('family_id','=',$ff->id)->get();
		$personmap = $this->copymembers($sf->id,$ffmembers);

		// Copy all relations from old families to new families
		$relations = Relation::where('family_id','=',$ff->id)->get() ;
		$this->copyrelations($sf->id,$relations,$personmap) ;

		// for all common members, if member has relation stored with fm for ff then no issue.
		// otherwise find relation b/w fm and member and save
		$ffgraph = new FamilyGraph($ff->id) ;
		$common = $ffgraph->getcommon($request->firstmember,$request->secondmember);
		foreach ($common  as $comid) {
			$rel1 = Relation::where('family_id','=',$ff->id)->where('person_id','=',$comid)->first();
			$rel2 = Relation::where('family_id','=',$ff->id)->where('relative_id','=',$comid)->first();
			if(  (($rel1 != null) && $request->firstmember == $rel1->relative_id )
			   ||(($rel2 != null) && $request->firstmember == $rel2->person_id) ){
				//add relation in second family
				$newrel = $ffgraph->FindRelationsBetween($request->secondmember, $comid) ;
 				$relation = new Relation ;
 			 	$relation->user_id 	= \Auth::id() ;
 			 	$relation->family_id = $sf->id ;
 			 	$relation->person_id = $personmap[$request->secondmember] ;
 			 	$relation->relation = $newrel ;
 			 	$relation->relative_id = $personmap[$comid] ;
 			 	$relation->save() ;
			}
			else {
				// add relation in first family
				$newrel = $ffgraph->FindRelationsBetween($request->firstmember, $comid) ;
 				$relation = new Relation ;
 			 	$relation->user_id 	= \Auth::id() ;
 			 	$relation->family_id = $ff->id ;
 			 	$relation->person_id = $request->firstmember ;
 			 	$relation->relation = $newrel ;
 			 	$relation->relative_id = $comid ;
 			 	$relation->save() ;
			}
		}

		//2. for family f1 delete member m, if p2 is in path between shortest path of p1 and member m 
		//   and same for f2
		//$ffgraph = new FamilyGraph($ff->id) ;
		$nomembers = $ffgraph->splitremove($request->firstmember,$request->secondmember); 
		$this->create_missing_relation($ff->id,$ffgraph,$request->secondmember,$nomembers) ;
		Person::whereIn('id',$nomembers)->delete();
		Relation::where('family_id','=',$ff->id)->whereIn('person_id',$nomembers)->delete();
		Relation::where('family_id','=',$ff->id)->whereIn('relative_id',$nomembers)->delete();

		$sfgraph = new FamilyGraph($sf->id);
		$first_mem_in_sf = $personmap[$request->firstmember] ; // in 2nd family IDs are different
		$second_mem_in_sf = $personmap[$request->secondmember] ; // in 2nd family IDs are different
		$nomembers = $sfgraph->splitremove($second_mem_in_sf,$first_mem_in_sf);
		$this->create_missing_relation($sf->id,$sfgraph,$first_mem_in_sf,$nomembers) ;
		Person::whereIn('id',$nomembers)->delete();
		Relation::where('family_id','=',$sf->id)->whereIn('person_id',$nomembers)->delete();
		Relation::where('family_id','=',$sf->id)->whereIn('relative_id',$nomembers)->delete();

		//3. copy access for second family
		$ffaccesses = familyaccesse::where('family_id','=',$ff->id)->get();
		foreach ($ffaccesses as $key => $value) {
			 $famacess = new familyaccesse ;
			 $famacess->user_id = $value->user_id ;
			 $famacess->family_id = $sf->id ;
			 $famacess->aceess_type = $value->aceess_type ;
			 $famacess->save() ;
		}

		return redirect("/home");
	}

	private function create_missing_relation($famid,$famgraph,$pid,$nomembers){
		// 1. Get all connected IDs
		$connected = $famgraph->get_connected_ids($pid) ;
		// 2. Keep only those which are not in NOMEMBERS( delete if in NOMEMBERS array)
		foreach ($connected as $key => $value) {
			if(in_array($value, $nomembers)){
				unset($connected[$key]);
			}
		}
		// 3. check if person in array has more then one records in Relations table.
		foreach ($connected as $key => $value) {
			$c1 = Relation::where('family_id','=',$famid)->where('person_id','=',$value)->count();
			$c2 = Relation::where('family_id','=',$famid)->where('relative_id','=',$value)->count();
			// 4. if Yes then no worry safe to delete from relation table.
			// 5. if No (not more than one record)then create a new Relation with one of the person in step 1
			if(($c1 + $c2) <= 1){  // if has less than 2 records (1 or less)
				foreach ($connected as  $nxtid) {
					if($nxtid == $value) continue ;
					$newrel = $famgraph->FindRelationsBetween($value, $nxtid) ;
	 				$relation = new Relation ;
	 			 	$relation->user_id 	= \Auth::id() ;
	 			 	$relation->family_id = $famid ;
	 			 	$relation->person_id = $value ;
	 			 	$relation->relation = $newrel ;
	 			 	$relation->relative_id = $nxtid ;
	 			 	$relation->save() ;
	 			 	break ;
				}
			}
		}
	}


	



} //class
