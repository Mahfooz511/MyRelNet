<?php namespace App\Http\Controllers;

use App\Http\Requests\CreatePersonRequest;
use App\Http\Controllers\Controller;
use App\Person ;

	
class PersonController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}


	public function create(){
		$userfamilies = Family::get();
		foreach ($userfamilies as $key => $value) {
			$familyarray[$value->id] = $value->name ; 
		}
		return view('addperson',['familyarray' => $familyarray] );
	}

	public function store(CreatePersonRequest $request){
		//$input = $request->all() ;
		//$input['user_id'] = 100 ;

		$person = new Person($request->all()) ;

		\Auth::user()->persons()->save($person);
		//return dd($input)->toArray ;
		//Person::create($input);
		return redirect('/');
	}

	public function update($id, CreatePersonRequest $request){
		$person = Person::FindOrFail($id) ;

		$person->update($request->all()) ;

		return redirect('/');
	}

	public function show($id){

		//$person = Person::where('user_id' ,'=',\Auth::id())->FindOrFail($id) ;
		$person = Person::FindOrFail($id) ;
 
 		return dd($person)->toArray() ;
	}

	public function edit($id){
		$person = Person::FindOrFail($id) ;

		return view('personedit',compact('person')) ;
	}

}
