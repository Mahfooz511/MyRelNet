<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Share ;
use App\ShareActivation ;
use App\familyaccesse ;
use Carbon\Carbon ;
use RelNet\Misc;





class ShareController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');

	}

	
	private function valid($shareid){
		$share = Share::where('shareid','=',$shareid)->get() ;
		if(isset($share) && $share[0]->type == 'Single' && $share[0]->valid){
			return True;
		}elseif(isset($share) && $share[0]->type == 'Group') {
			switch ($share[0]->validity) {
			    case 1:
			        $days = 7 ;
			        break;
			    case 2:
			        $days = 14 ;
			        break;
			    case 3:
			        $days = 30 ;
			        break;
			}
			$now = \Carbon::now();
			$created = new Carbon($share[0]->created_at);
			if($created->diff($now)->days <=  $days){
				return True ;
			}
			else {
				return False;
			}
		}
		return False;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$sid = $request->sid ;
		
		$share = Share::where('shareid','=',$sid)->get() ;
		// If user himself clicks the link
		if($share[0]->user_id == \Auth::id()){
			return redirect('/');
		}

		if($this->valid($sid)){
			$familyaccess = new familyaccesse ;
			$familyaccess->user_id = \Auth::id() ;
			$familyaccess->family_id = $share[0]->family_id ;
			$familyaccess->aceess_type = $share[0]->access_type ;
			$familyaccess->save() ;

			$share_id = Share::where('shareid','=',$sid)->get(['id'])[0]->id ;
			$shareactivation = new ShareActivation ; //::where('share_id','=',$share_id)
			$shareactivation->user_id = \Auth::id() ;
			$shareactivation->share_id = $share[0]->id ;
			$shareactivation->activation_date =	date("Y/m/d") ;
			$shareactivation->save() ;

			if($share[0]->type == 'Single'){
				$share[0]->valid = False ;
				$share[0]->save();
			}
		}
		else {
			abort();
		}

		return redirect('/');
	}

	public function shareform($famid){

		return view('share', ['famid' => $famid]);
	}

	// sharestore
	public function share(Request $request){
		$share = new Share ;
		$share->user_id = \Auth::id() ;
		$share->family_id = $request->family_id ;
		$share->type = $request->sharetype ;
		$share->validity = $request->validity ;
		$share->shareid = str_random(40);
		$share->access_type = strtolower($request->access) ;
		$share->save();

		$message = "This link is valid for " ;
		if($request->sharetype == "Single"){
			$message .= "one user." ; 
		}else{
			$message .= "multiple users, " ; 
			if($request->validity == 1){
				$message .= "for one week." ; 
			}
			if($request->validity == 2){
				$message .= "for two weeks." ; 
			}
			if($request->validity == 3){
				$message .= "for one month." ; 
			}
		}

		return view('shareconfirm', ['famid' => $request->family_id, 
			                         'shareid' => $share->shareid,
			                         'message' => $message, ]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	//Change access rights Form
	public function access($famid) {
		
		Misc::isfam_valid($famid,"Edit");
		
		$sql = "SELECT u.name,u.id,u.email, fa.aceess_type
				FROM familyaccesses fa, users u
				where fa.family_id = :id
				and u.id = fa.user_id 
				and fa.deleted_at is  NULL " ;
		$famaccess = \DB::select($sql, ['id' => $famid]) ; 		
		$owner = false ;
		foreach ($famaccess as $key => $access) {
			if($access->id == \Auth::id() && $access->aceess_type == "owner"){
				$owner = true;
				break;
			}
		}
		return view('accesschange',compact('famaccess','owner','famid')) ;
	}

	//Change access store in DB
	public function accessstore(Request $request){
		$famid = $request->family_id ;
		Misc::isfam_valid($famid,"ChangeAccess");
		$currentuser = \Auth::id() ;
		$sql = "SELECT u.name,u.id ,u.email,fa.id as faid, fa.aceess_type
				FROM familyaccesses fa, users u
				where fa.family_id = :id
				and u.id = fa.user_id 
				and fa.deleted_at is  NULL" ;
		$famaccess = \DB::select($sql, ['id' => $famid]) ; 	
		
		foreach ($famaccess as $key => $access) {
			if($access->aceess_type == 'owner'){
				$owner = $access->id ;
				break;
			}
		}
		foreach ($famaccess as $key => $access) {
			// if currentuser is not owner, and this user(in loop) is not owner, 
			// and request is sent for changing ownership, then its not allowed.
			// Only Owner can change ownership
			$tempid = $access->id ;
			if($currentuser != $owner && $access->aceess_type != 'owner' && $request->$tempid == 'owner'){
				return Redirect::back()->withErrors(['msg', "You can't change ownership of a family you dont own."]);
			}
		}

		// any potential hacking effort is handled above. just update Db now
		foreach ($famaccess as $key => $access) {	
			$tempid = $access->id ;
			if($request->$tempid != 'noaccess'){
				$familyaccess_update = familyaccesse::find($access->faid) ;
				$familyaccess_update->aceess_type = $request->$tempid ;
				$familyaccess_update->save() ;	
			}			 
			elseif ($request->$tempid == 'noaccess') {
				$familyaccess_update = familyaccesse::find($access->faid) ;
				$familyaccess_update->delete() ;				 
			}
		}
		
		//return redirect("family/$famid");
		return \Redirect::to("family/$famid")->with('message', 'Access rights updated.');
	}


}
