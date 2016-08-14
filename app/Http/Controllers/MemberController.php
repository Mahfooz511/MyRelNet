<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Person ;
use App\Family ;
use App\Relation ;
use App\Userpreferrence ;
use RelNet\FamilyGraph;
use RelNet\Misc;
use App\familyaccesse ;



class MemberController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	// get the name + nick name in logical way
	private function getfullname($id, $name, $nickname){
		if($name == "" &&  $nickname == ""){
			$name = "ANONYMOUS" . $id;
		}else if($name == "" &&  $nickname != ""){
			$name = $nickname ;
		} else if($name != "" &&  $nickname != ""){
			$name =  $name . " ( " . $nickname . " )" ;
		}
		return($name);
	}

	// return image as content after validation
	public function image($file)
	{
		// check if the file exists
		if (file_exists(public_path() . '/user/img/' .$file)) {
		    // Get the file content to put into your response
		    $content = file_get_contents(public_path() . '/user/img/' .$file);
		    //Build your Laravel Response with your content, the HTTP code and the Header application/pdf
		    return \Response::make($content, 200, array('content-type'=>'image/jpeg'));
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($famid)
	{
		$userfamily = Family::getto("Edit")->where('id','=',$famid)->get();
		
		$familyarray[$famid] = $userfamily[0]->name ; 


		// Check if this family has 0 members
		$members_count = Person::where('family_id', '=', $famid)->count();
		if($members_count == 0 ) {
			// Add first member
			return view('addmember',['familyarray' => $familyarray, 'firstmember' => True, 'famid' => $famid]);
		}

		// Family already has one or more members.

		$relatives = Person::where('family_id', '=', key($familyarray))->get();
		//var_dump($relatives);
		foreach ($relatives as $key => $value) {
			$relativearray[$value->id] = $this->getfullname($value->id, $value->name, $value->nickname) ;
		}	

		$location = Person::where('family_id', '=', key($familyarray))
							->whereNotNull('city')
							->whereNotNull('state')
							->whereNotNull('country')
							->distinct()
							->get(['city','state','country'])
						 	->toJson();

		//$location = "HELLOW HELLOW";						 	

		$relationarray = Misc::get_relationarray() ;

		return view('addmember',['familyarray' => $familyarray, 'relationarray' => $relationarray,
								 'relativearray' => $relativearray,  'firstmember' => False,
								 'famid' => $famid,
								 'location' => htmlspecialchars($location,ENT_NOQUOTES)
								 ] );
	}

	// Generate Member Delete Form
	public function delete($famid)
	{
		$userfamily = Family::getto("Edit")->where('id','=',$famid)->get();
		$familyarray[$famid] = $userfamily[0]->name ; 
		// Check if this family has 0 members
		$members_count = Person::where('family_id', '=', $famid)->count();

 
		// Family  has one or more members.
		$members = Person::where('family_id', '=', $famid)->get(['id','name','nickname']);
		$membersarray = array() ;
		foreach ($members as $key => $value) {
			$membersarray[$value->id] = $this->getfullname($value->id, $value->name, $value->nickname) ;
		}

		return view('deletemember',['members' => $membersarray,
									'members_count' => $members_count, 
									'familyarray' => $familyarray,
									'famid' => $famid ]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		foreach (Family::getto("Edit")->get(['id']) as $key => $value) {
 	 		$famarray[$key] = $value->id ;
 	 	}
 	 	if(! in_array($request->family_id, $famarray )){
 	 		return redirect('/') ;
 	 	}
		//echo dd($request)->toArray() ;
		$person = new Person();
		$person->family_id = $request->family_id ;
		$person->name = $request->name ;
		$person->nickname = $request->nickname ;
		if($request->firstmember) {
			$person->gender = $request->gender ;
			$person->generation = 1000 ; // first member has gen number 1000
		}else{
			if(in_array($request->relation,['Maan','Behan','Beti','Patni']) ){
				$person->gender = 'Female' ;
			}elseif(in_array($request->relation,['Pita','Bhai','Beta','Pati'])){
				$person->gender = 'Male' ;
			}else{
				abort();
			}
			// Calculate  generation, when not the first member
			$relgen = Person::where('id','=',$request->relative_id)->get(['generation'])[0]->generation ;
			if(in_array($request->relation,['Maan','Pita']) ){
				$person->generation = $relgen - 1 ;
			}elseif(in_array($request->relation,['Behan','Patni','Bhai','Pati']) ){
				$person->generation = $relgen ;
			}elseif(in_array($request->relation,['Beti','Beta']) ){
				$person->generation = $relgen + 1 ;
			}
		}
		if($request->deadoralive == "dead") $person->deadoralive = "dead";
		if($request->siblingno != "" ) $person->siblingno = $request->siblingno + 1;
		$person->age = $request->age ;		
		$person->city = $request->city ;
		$person->state = $request->state ;
		$person->country = $request->country ;
		$person->description = $request->notes ;
		// Image loading logic
		if (\Input::hasFile('image')) { 
		$file = array('image' => $request->file('image'));
		// setting up rules
		$rules = array('image' => 'image',); //mimes:jpeg,bmp,png and for max size max:10000
		// doing the validation, passing post data, rules and the messages
		$validator = \Validator::make($file, $rules);
		if ($validator->fails()) {
			// send back to the page with the input data and errors
			return \Redirect::to("family/$request->family_id/person/add")->withInput()->withErrors($validator);
		}
		else {
			// checking file is valid.
			if ($request->file('image')->isValid()) {
			  $destinationPath = 'user/img'; // upload path
			  $extension = \Input::file('image')->getClientOriginalExtension(); // getting image extension			  
			  $person->image = $extension ;
			  // sending back with message
			  \Session::flash('success', 'Upload successfully'); 
			}
			else {
			  // sending back with error message.
			  \Session::flash('error', 'uploaded file is not valid');
			  return Redirect::to('addmember');
			}    
		} //else
		}
		// /Image loading logic

		\Auth::user()->persons()->save($person);
		if(isset($extension)){
			$dstfilename = $person->id . "f". $person->family_id .".". $extension ;
			// \Input::file('image')->move($destinationPath, $dstfilename); // uploading file to given path
			\Input::file('image')->move($destinationPath, $dstfilename . '.org');
			$this->scaleImageFileandSave($destinationPath . '/' . $dstfilename . '.org', $destinationPath . '/' . $dstfilename) ;
			if ( ! unlink( $destinationPath . '/' . $dstfilename . '.org') )
			{
			    die("Couldn't delete file " . $dstfilename . '.org');
			}
		}

		if(! $request->firstmember){
			$relation = new Relation();
			$relation->family_id = $request->family_id ;
			$relation->user_id =  \Auth::id() ; // current logged in user id 
			$relation->person_id = $person->id  ; //this new members id
			$relation->relation  = $request->relation ;
			$relation->relative_id = $request->relative_id ;
			$relation->save();
		}	
		return redirect()->action('FamilyController@show',[$request->family_id]);
	}


	public function getmembers(){
		$input = \Input::get('option');
		foreach (Family::getto("Show")->get(['id']) as $key => $value) {
 	 		$famarray[$key] = $value->id ;
 	 	}
 	 	if(! in_array($input, $famarray )){
 	 		return redirect('/') ;
 	 	}

	 	$persons = Person::where('family_id','=',$input)->get(['id','name']);
	 	return \Response::make($persons);
	}

	// Find member form , Ajax call
	public function find(){
		$famid = \Input::get('famid');
		foreach (Family::getto("Show")->get(['id']) as $key => $value) {
 	 		$famarray[$key] = $value->id ;
 	 	}
 	 	if(! in_array($famid, $famarray )){
 	 		return redirect('/') ;
 	 	}

	 	$members = Person::where('family_id','=',$famid)->get(['id','name','nickname']);
	 	
		//echo $members[0]->id ;
		foreach ($members as $key => $value) {
			$members[$key]->name = $this->getfullname($value->id, $value->name, $value->nickname) ;
		}
	 	return \Response::make($members);
	}

	// Member info for ajax call
	public function memberinfo(){
		$pid = \Input::get('id');
		Misc::isid_valid($pid, "Show");
		
 	 	if(! Misc::isid_valid($pid, "Show")){
 	 		// invalid access - to handle later
 	 		return redirect('/') ;
 	 	}

	 	$member = Person::where('id','=',$pid)->get(['id','family_id','name','nickname','city','image','gender'])[0];	 	
	 	if($member->image != null) {
	 		$member->image = $pid ."f".$member->family_id.".".$member->image ;
	 	} 
		$member->name = $this->getfullname($member->id, $member->name, $member->nickname) ;

		$sql = "select A.id, B.relative_id, A.name , B.relation, C.name as relname 
		        from persons A, relations B, persons C
		        where A.id = B.person_id 
		        and C.id = B.relative_id
		        and (B.person_id = :id or B.relative_id = :id2) " ;

 		$relatives = \DB::select($sql, ['id' => $pid, 'id2' => $pid]) ; 		
 		$member->relative = "";
 		$member->relation = "" ;
 		if($relatives[0]->id == $pid){
 			$member->relative  = $relatives[0]->relname ;
 			$member->relation = Misc::get_relation_in_mylang($relatives[0]->relation) ; 			
 		} else{
 			$member->relative  = $relatives[0]->name ;
 			$member->relation = Misc::get_revrelation_in_mylang($relatives[0]->relation,$member->gender); 			
 		}

	 	return \Response::make($member);
	}

	// response to ajax call Relation find b/w 2 members
	public function findrelation(){
		$famid  = \Input::get('fid'); 
		$memid1 = \Input::get('rfid1');
		$memid2 = \Input::get('rfid2');
		Misc::isfam_valid($famid,"Show");

		if($memid1 == $memid2) return \Response::make("SELF");
		$famgraph = new FamilyGraph($famid);
 	 	
 	 	$relation = $famgraph->FindRelationsBetween($memid1,$memid2) ;
 	 	return \Response::make(Misc::get_relation_in_mylang($relation) );
	}


	public function getmemberinfo(){
		$input = \Input::get('option');
	 	$person = Person::where('id','=',$input)->get(['name','location','family_id']);
	 	// foreach(Family::getto("Show")->get(['id']) as $key => $value) {
 	 // 		$famarray[$key] = $value->id ;
 	 // 	}
 	 // 	if(! in_array($person[0]->family_id, $famarray )){
 	 // 		return redirect('/') ;
 	 // 	}
	 	Misc::isid_valid($input,"Show");

	 	$relatives = Relation::where('person_id','=',$input)->take(2)->get(['relation','relative_id']) ;
	 	$memberinfo["location"] = $person[0]->location ;
	 	foreach ($relatives as $key => $value) {
	 		$relative = Person::where('id','=',$value->relative_id)->get(['name']);
	 		$memberinfo["relname".$key] = $relative[0]->name ;
	 		$memberinfo["relation".$key] = $value->relation ;
	 	}
	 	return \Response::make($memberinfo);
	}

	public function deleteinfo(){
		$pid = \Input::get('option');
		$fid = \Input::get('famid');

		// foreach(Family::getto("Show")->get(['id']) as $key => $value) {
 	//  		$famarray[$key] = $value->id ;
 	//  	}
 	//  	if(! in_array($fid, $famarray )){
 	//  		return redirect('/') ;
 	//  	}
	 	Misc::isfam_valid($fid,"Show");

	 	//pass the id of person in class family and get YEs/No for more then one family connection
	 	// and an array of ids of people connected to her
	 	// if ok to delete then  empty array YES
 	 	$famgraph = new FamilyGraph($fid);

 	 	$deleteinfo = $famgraph->check_delete($pid);
 	 	foreach ($deleteinfo["neighbours"] as $id => $value) {
	 		$relative = Person::where('id','=',$id)->get(['name']);
	 		$deleteinfo["neighbours"][$id] = $relative[0]->name ;
	 	}
	 	
	 	return \Response::make(json_encode($deleteinfo));
	}

	public function remove(Request $request){
		$pid = $request->delmemberlist;
		$fid = $request->family_id ;

		// foreach(Family::getto("Edit")->get(['id']) as $key => $value) {
 	//  		$famarray[$key] = $value->id ;
 	//  	}
 	//  	if(! in_array($fid, $famarray )){
 	//  		return redirect('/') ;
 	//  	}
 	 	Misc::isfam_valid($fid,"Edit");
	 	//pass the id of person in class family and get YEs/No for more then one family connection
	 	// and an array of ids of people connected to her
	 	// if ok to delete then  empty array YES
	 	
 	 	$famgraph = new FamilyGraph($fid);

 	 	$deleteinfo = $famgraph->check_delete($pid);
 	 	// connecting two families, cant be deleted
 	 	if($deleteinfo["connector"]) {
 	 		\App::abort(403, 'Member cant be deleted');
 	 	}

 	 	// get all connecting persons from Relations Table
 	 	$query1 = Relation::where('person_id','=',$pid)->get(['id','relative_id'])  ;
 	 	$query2 = Relation::where('relative_id','=',$pid)->get(['id','person_id'])  ;
 	 	$connected = array() ;
 	 	foreach ($query1 as $key => $value) {
 	 		$connected[$value->id] = $value->relative_id ;
 	 	}
 	 	foreach ($query2 as $key => $value) {
 	 		$connected[$value->id] = $value->person_id ;
 	 	}
 	 	// if one or no connection then safe to delete, else make new connection in Relation tbl
 	 	if(count($connected) > 1){
 	 		foreach ($connected as $key => $person_id1) {
 	 			foreach ($connected as $key => $person_id2) {
 	 				if($person_id1 == $person_id2) continue ;
 	 				$newrel = $famgraph->FindRelationsBetween($person_id1, $person_id2) ;
 	 				$relation = new Relation ;
 	 			 	$relation->user_id 	= \Auth::id() ;
 	 			 	$relation->family_id = $fid ;
 	 			 	$relation->person_id = $person_id1 ;
 	 			 	$relation->relation = $newrel ;
 	 			 	$relation->relative_id = $person_id2 ;
 	 			 	$relation->save() ;
 	 			 	break 2;
 	 			}
 	 		}
 	 	}

 	 	// remove person from Relation and Person tbl
 	 	foreach ($connected as $key => $person_id1) {
 	 		Relation::find($key)->delete() ;
 	 	}
 	 	$personname = Person::where('id','=',$pid)->get(['name']) ;
 	 	$familyname = Family::where('id','=',$fid)->get(['name']) ;
 	 	Person::find($pid)->delete() ;
	 	
		return view('person_deleted', ['person' => $personname[0]->name, 'familyname' => $familyname[0]->name,'famid' => $fid]);
	}


	public function edit($famid){
		
		$pid = \Input::get('pid');
		if($pid != null){
			$firsperson = $pid ;
		}

		$userfamily = Family::getto("Edit")->where('id','=',$famid)->get();
		$familyarray[$famid] = $userfamily[0]->name ; 
		$info = new \stdClass();  // create object to add attributes
 		$info->relative = "";
 		$info->relation = "" ;
		// Check if this family has 0 members
		$members_count = Person::where('family_id', '=', $famid)->count();
		// Family  has one or more members.
		$members = Person::where('family_id', '=', $famid)->get(['id','name','nickname','gender']);
		$membersarray = array() ;
		$flag = false;
		foreach ($members as $key => $value) {
			$membersarray[$value->id] = $this->getfullname($value->id,$value->name,$value->nickname);
			if(! $flag){
				if($pid != null && $value->id == $pid){
					$info->gender = $value->gender ;
					$flag = true;
				}else if($pid == null){
					$info->gender = $value->gender ;
					$flag = true;
				}	
			}
		}

		$relatives = Person::where('family_id', '=', $famid)->get();
		$relativearray = array() ;
		foreach ($relatives as $key => $value) {
			$relativearray[$value->id] = $value->name  ; 
		}
		
		$relationarray = Misc::get_relationarray() ;
		
		if(! isset($firsperson)){
			$firsperson = key($membersarray);
		}
		if($firsperson != Null){
			$firstpersondata = Person::FindorFail($firsperson);
		}
		else{$firstpersondata = "";}

		$relationdata = Relation::where('person_id','=', $firsperson)->get(['relative_id','relation']); 
		$relative = null ;
		$relation = null ;
		if($relationdata->count() != 0){
			$relative = $relationdata[0]->relative_id ;
			$relation = $relationdata[0]->relation;
		}
		//////////////////
		$sql = "select A.id, B.relative_id, A.name , B.relation, C.name as relname 
		        from persons A, relations B, persons C
		        where A.id = B.person_id 
		        and C.id = B.relative_id
		        and (B.person_id = :id or B.relative_id = :id2) " ;

 		$myrelatives = \DB::select($sql, ['id' => $firsperson, 'id2' => $firsperson]) ; 		
 		if($myrelatives[0]->id == $firsperson){
 			$info->relative  = $myrelatives[0]->relname ;
 			$info->relation = Misc::get_relation_in_mylang($myrelatives[0]->relation) ; 			
 		} else{
 			$info->relative  = $myrelatives[0]->name ;
 			$info->relation = Misc::get_revrelation_in_mylang($myrelatives[0]->relation,$info->gender); 			
 		}
		/////////////////

		$location = Person::where('family_id', '=', key($familyarray))
							->whereNotNull('city')
							->whereNotNull('state')
							->whereNotNull('country')
							->distinct()
							->get(['city','state','country'])
						 	->toJson();

		return view('editform',['members' => $membersarray,
									'members_count' => $members_count, 
									'familyarray' => $familyarray,
									'famid' => $famid,
									'relationarray' => $relationarray,
									'relativearray' => $relativearray,
									'firstpersondata' => $firstpersondata,
									'relative' => $relative,
									'relation' => $relation,
									'location' => htmlspecialchars($location,ENT_NOQUOTES),
									'info' => $info,
									]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function editstore(Request $request)
	{
 	 	Misc::isfam_valid($request->family_id,"Edit");
		//echo dd($request)->toArray() ;
		$person = Person::find($request->editmemberlist);
		$oldimage = $person->image ;
		$person->name = $request->name ;
		$person->nickname = $request->nickname ;
		$person->siblingno = null ;
		if($request->siblingno != "" ) $person->siblingno = $request->siblingno + 1;
		$person->city = $request->city ;
		$person->state = $request->state ;
		$person->country = $request->country ;
		$person->description = $request->notes ;

		if($request->deadoralive == "dead") $person->deadoralive = "dead";
		if($request->gender != null) {
			$person->gender = $request->gender ;
		}else{
			if(in_array($request->relation,['Maan','Behan','Beti','Patni']) ){
				$person->gender = 'Female' ;
			}elseif(in_array($request->relation,['Pita','Bhai','Beta','Pati'])){
				$person->gender = 'Male' ;
			}else{
				abort(404);
			}
		}
		// Calculate  generation, when not the first member
		if($request->gender == null) {
			$relgen = Person::where('id','=',$request->relative_id)->get(['generation'])[0]->generation ;

			if(in_array($request->relation,['Maan','Pita']) ){
				$person->generation = $relgen - 1 ;
			}elseif(in_array($request->relation,['Behan','Patni','Bhai','Pati']) ){
				$person->generation = $relgen ;
			}elseif(in_array($request->relation,['Beti','Beta']) ){
				$person->generation = $relgen + 1 ;
			}
		}
		
		// Image loading logic
		if ($request->hasFile('image')) { 
			$file = array('image' => $request->file('image'));
			// setting up rules
			$rules = array('image' => 'image',); //mimes:jpeg,bmp,png and for max size max:10000
			// doing the validation, passing post data, rules and the messages
			$validator = \Validator::make($file, $rules);
			if ($validator->fails()) {
				// send back to the page with the input data and errors
				return \Redirect::to("family/$request->family_id/person/edit")->withInput()->withErrors($validator);
			}
			else {
				// checking file is valid.
				if ($request->file('image')->isValid()) {
				  $destinationPath = 'user/img'; // upload path
				  $extension = \Input::file('image')->getClientOriginalExtension(); // getting image extension				  
				  $person->image = $extension ;
				  // sending back with message
				  \Session::flash('success', 'Upload successfully'); 
				 // return Redirect::to('upload');
				}
				else {
				  // sending back with error message.
				  \Session::flash('error', 'uploaded file is not valid');
				  return \Redirect::to('edit');
				}    
			} //else
		} // /Image loading logic

		if($request->imgremove == 1) {
			$person->image = null ;
		}

		\Auth::user()->persons()->save($person);
		

		// if there was an image and needs to be changed, OR needs to be deleted
		if(($oldimage != null && \Input::hasFile('image')) || $request->imgremove == 1 ){
			// move old image for deletion
			$fname = "user/img/" . $person->id . "f" . $person->family_id . "." .$oldimage ;
			if ( ! \File::move($fname, $fname . ".DELETE"))
			{
			    die("Couldn't rename file");
			}
		}
		if(isset($extension)){			
			// \Input::file('image')->move($destinationPath, $person->id . "f" . $person->family_id . ".". $extension); // uploading file to given path

			$dstfilename = $person->id . "f". $person->family_id .".". $extension ;
			// \Input::file('image')->move($destinationPath, $dstfilename); // uploading file to given path
			\Input::file('image')->move($destinationPath, $dstfilename . '.org');
			$this->scaleImageFileandSave($destinationPath . '/' . $dstfilename . '.org', $destinationPath . '/' . $dstfilename) ;
			if ( ! unlink( $destinationPath . '/' . $dstfilename . '.org') )
			{
			    die("Couldn't delete file " . $dstfilename . '.org');
			}
		}

		// if not first person then update relations
		$tempcheck = $request->relation ;
		if( isset($tempcheck) ){
			$relationdata = Relation::where('person_id','=', $request->editmemberlist)->get(['id', 'relative_id','relation']); 
			$relation =  Relation::find($relationdata[0]->id);
			//$relation->family_id = $request->family_id ;
			$relation->user_id =  \Auth::id() ; // current logged in user id 
			//$relation->person_id = $person->id  ; //this new users id
			$relation->relation  = $request->relation ;
			$relation->relative_id = $request->relative_id ;
			$relation->save();
		}	
		\Session::flash('success', 'Member Edited Successfully.'); // To check later
		return redirect()->action('FamilyController@show',[$request->family_id]);
	}

	// Show Family in a list
	public function showlist($famid){
		Misc::isfam_valid($famid,"Show");

		$members = Person::where('family_id','=',$famid)->get(['id','family_id','name','nickname','city','state','country','image','gender','deadoralive']);	 	
	 	
		$sql = "select A.id, B.relative_id, A.name , B.relation, C.name as relname 
			        from persons A, relations B, persons C
			        where A.id = B.person_id 
			        and C.id = B.relative_id
			        and (B.person_id = :id or B.relative_id = :id2) " ;

		foreach ($members as $key => $member) {
			if($member->image != null) {
		 		$member->image = $member->id ."f".$member->family_id.".".$member->image ;
		 	} 
			$member->name = $this->getfullname($member->id, $member->name, $member->nickname) ;
	 		$relatives = \DB::select($sql, ['id' => $member->id, 'id2' => $member->id]) ; 		
	 		$member->relative = "";
	 		$member->relation = "" ;
	 		if($relatives[0]->id == $member->id){
	 			$member->relative  = $relatives[0]->relname ;
	 			$member->relation = Misc::get_relation_in_mylang($relatives[0]->relation) ; 			
	 		} else{
	 			$member->relative  = $relatives[0]->name ;
	 			$member->relation = Misc::get_revrelation_in_mylang($relatives[0]->relation,$member->gender); 			
	 		}
			
		}
		return view('list', compact('members','famid'));
	}


	public function setroot($famid) {
		$userfamily = Family::getto("Show")->where('id','=',$famid)->get();
		$familyarray[$famid] = $userfamily[0]->name ; 
		// Check if this family has 0 members
		$members_count = Person::where('family_id', '=', $famid)->count();

		// Family  has one or more members.
		$members = Person::where('family_id', '=', $famid)->get(['id','name','nickname']);
		$membersarray = array() ;
		foreach ($members as $key => $value) {
			$membersarray[$value->id] = $this->getfullname($value->id, $value->name, $value->nickname) ;
		}

		return view('setroot',['members' => $membersarray,
									'members_count' => $members_count, 
									'familyarray' => $familyarray,
									'famid' => $famid ]);
	}

	public function setrootstore(Request $request){
		$pid = $request->memberlist;
		$fid = $request->family_id ;

 	 	Misc::isfam_valid($fid,"Show");
	 	
	 	$famaccess = familyaccesse::where('family_id','=',$fid)->where('user_id','=',\Auth::id())->get()[0];
	 	//$famaccess = familyaccesse::find($famaccess_id);
	 	//dd($request,$famaccess);
	 	$famaccess->root_id = $request->memberlist ;
	 	$famaccess->save();
	 	
		return redirect("family/$fid");
		
	}

	private function scaleImageFileandSave($file, $dstfile) {

	    $source_pic = $file;
	    $max_width = 300;
	    $max_height = 300;

	    list($width, $height, $image_type) = getimagesize($file);

	    switch ($image_type)
	    {
	        case 1: $src = imagecreatefromgif($file); break;
	        case 2: $src = imagecreatefromjpeg($file);  break;
	        case 3: $src = imagecreatefrompng($file); break;
	        default: return '';  break;
	    }

	    $x_ratio = $max_width / $width;
	    $y_ratio = $max_height / $height;

	    if( ($width <= $max_width) && ($height <= $max_height) ){
	        $tn_width = $width;
	        $tn_height = $height;
	        }elseif (($x_ratio * $height) < $max_height){
	            $tn_height = ceil($x_ratio * $height);
	            $tn_width = $max_width;
	        }else{
	            $tn_width = ceil($y_ratio * $width);
	            $tn_height = $max_height;
	    }

	    $tmp = imagecreatetruecolor($tn_width,$tn_height);

	    /* Check if this image is PNG or GIF, then set if Transparent*/
	    if(($image_type == 1) OR ($image_type==3))
	    {
	        imagealphablending($tmp, false);
	        imagesavealpha($tmp,true);
	        $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
	        imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
	    }
	    imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

	    $res = imagejpeg($tmp, $dstfile);

	    return $res ;
	}

	public function reladd($famid) {

		// check family vailidy 
		Misc::isfam_valid($famid,"Edit");
		$userfamily = Family::getto("Edit")->where('id','=',$famid)->get();
		$familyarray[$famid] = $userfamily[0]->name ; 

		// Get memebers list 
		$info = new \stdClass();  // create object to add attributes
 		$info->relative = "";
 		$info->relation = "" ;
		// Check if this family has less members
		$members_count = Person::where('family_id', '=', $famid)->count();

		// Family  has three or more members.
		$members = Person::where('family_id', '=', $famid)->get(['id','name','nickname','gender']);
		$membersarray = array() ;
		$flag = false;
		foreach ($members as $key => $value) {
			$membersarray[$value->id] = $this->getfullname($value->id,$value->name,$value->nickname);
		}

		
		$firsperson = key($membersarray);
		if($firsperson != Null){
			$firstpersondata = Person::FindorFail($firsperson);
		}
		else{
			$firstpersondata = "";
		}
		
		$relationarray = Misc::get_relationarray() ;

		// send data: member list and relationlist
		return view('addrelation', ['membersarray'  => $membersarray ,
									'relationarray' => $relationarray,
									'members_count' => $members_count,
									'familyarray'   => $familyarray,
									'firstpersondata' => $firstpersondata,
									'famid' => $famid ]);
	}

	public function reladdstore(Request $request){

		$pid = $request->relative_id;
		
 	 	// validate family
 	 	Misc::isfam_valid($request->family_id,"Edit");

 	 	// check if this relation already exists
		$count = Relation::where('family_id', '=',$request->family_id)
		        		   	->where('person_id','=',$pid)
		        			->where('relative_id','=',$request->relative_id2)
		        			->count();
			// relation can be stored in other way, check that too
		$count += Relation::where('family_id', '=',$request->family_id)
		        		   	->where('person_id','=',$request->relative_id2)
		        			->where('relative_id','=',$pid)
		        			->count();
		// if count > 0 that means relation exists already
		if($count > 0){
			return redirect()->back()->withInput()->withErrors(['Looks like a relation already exists between these members.']);
		}

		// Check if two members are same
// 		
// 

 	 	// Save the new relation
 	 	$relation = new Relation();
		$relation->family_id = $request->family_id ;
		$relation->user_id =  \Auth::id() ; // current logged in user id 
		$relation->person_id = $pid  ; //this new members id
		$relation->relation  = $request->relation ;
		$relation->relative_id = $request->relative_id2 ;
		$relation->extra = 'yes';
		$relation->save();

		//family_id, person_id, relation, relative_id
		// or family_id, person_id, relation, relative_id

		return redirect()->action('FamilyController@show',[$request->family_id]);

	}

	public function reldelete($famid) {

		// check family vailidy 
		Misc::isfam_valid($famid,"Edit");
		$userfamily = Family::getto("Edit")->where('id','=',$famid)->get();
		$familyarray[$famid] = $userfamily[0]->name ; 

		$count = Relation::where('family_id','=',$famid)->where('extra','=','yes')->count() ;
		
		$sql = "select B.id, A.name , B.relation, C.name as relname 
		        from persons A, relations B, persons C
		        where A.id = B.person_id 
		        and C.id = B.relative_id
		        and B.extra = 'yes'
		        and B.deleted_at is null
		        and B.family_id = :famid " ;

 		$relationlist = \DB::select($sql, ['famid' => $famid]) ; 

 		$relationarray = array() ;
 		foreach ($relationlist as $key => $value) {

 			$relationarray[$value->id] = $value->name . " is " . Misc::get_relation_in_mylang($value->relation) . " of " . $value->relname ;
 		}
 		//dd($relationarray);
 		return view('deleterelation', ['relationarray'  => $relationarray ,
 										'familyarray' => $familyarray,
 										'count' => $count,
										'famid' => $famid ]);

	}

	public function reldeletestore(Request $request){
		
 	 	// validate family
 	 	Misc::isfam_valid($request->family_id,"Edit");

 	 	$relation = Relation::find($request->relationlist);

 	 	$relation->delete() ;

 	 	\Session::flash('success', 'Relation Deleted Successfully.'); // To check later
		return redirect()->action('FamilyController@show',[$request->family_id]);


 	 }


}
