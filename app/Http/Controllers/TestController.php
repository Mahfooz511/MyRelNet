<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use RelNet\RelationMetaData;
use RelNet\FamilyGraph;
use App\Family;
use App\Person;

use Illuminate\Http\Request;

class TestController extends Controller {

	public function index(){
		$person =  Person::get()->toArray(); 
		dd($person);
		$newperson = new Person ;
		$newperson = $person ;
		$newperson->id = null ; 
		dd($newperson);
	}

	public function family(){
		$families = new Family ;  
		
		dd($families->getto()->get(['id','name']));

	}

} //TestController

?>