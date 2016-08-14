<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');
Route::get('welcome', 'WelcomeController@welcome');
Route::get('home', 'HomeController@newhome');
Route::get('/test', function(){return view('test');});

//Route::get('family/{famid}','FamilyController@famgraph') ;
Route::get('family/rename','FamilyController@rename') ;
Route::post('family/rename','FamilyController@nameupdate') ;
Route::get('family/delete','FamilyController@delete') ;
Route::post('family/delete','FamilyController@remove') ;
Route::get('family/join','FamilyController@join') ;
Route::post('family/join','FamilyController@joinstore');
Route::get('family/copy','FamilyController@copy') ;
Route::post('family/copy','FamilyController@copystore') ;
Route::get('family/split','FamilyController@split') ;
Route::post('family/split','FamilyController@splitstore') ;
Route::resource('family','FamilyController') ;
Route::get('family/join/members','FamilyController@getmembers');
Route::get('family/split/members','FamilyController@getmembers');
Route::get('family/{famid}/map','FamilyController@map');
Route::get('family/{famid}/list','MemberController@showlist');
Route::get('family/{famid}/setroot','MemberController@setroot');
Route::post('member/setroot','MemberController@setrootstore');
Route::get('family/{famid}/access','ShareController@access');
Route::post('family/{famid}/access','ShareController@accessstore');






Route::get('family/{famid}/share','ShareController@shareform') ;
Route::post('family/share','ShareController@share') ;
Route::get('share','ShareController@index') ; //new user clicks the link

//Route::resource('person','PersonController') ;
Route::resource('member','MemberController') ;
Route::resource('family/{famid}/person/add','MemberController@create') ;
Route::resource('family/{famid}/person/delete','MemberController@delete') ;
Route::get('family/{famid}/person/edit','MemberController@edit') ;
Route::get('family/{famid}/person/edit/{pid}', function($famid,$pid){
	return \Redirect::action('MemberController@edit', array('famid' => $famid, 'pid' => $pid));
}) ; 
Route::post('family/{famid}/person/edit','MemberController@editstore');
Route::post('member/store','MemberController@store') ;
Route::post('member/remove','MemberController@remove') ;

Route::get('family/{famid}/relation/add','MemberController@reladd') ;
Route::post('family/{famid}/relation/add','MemberController@reladdstore') ;
Route::get('family/{famid}/relation/delete','MemberController@reldelete') ;
Route::post('family/{famid}/relation/delete','MemberController@reldeletestore') ;



// image 
Route::get('img/{file}','MemberController@image') ;
	

Route::filter('csrf', function() {
    $token = Request::ajax() ? Request::header('X-CSRF-Token') : Input::get('_token');
    if (Session::token() != $token)
        throw new Illuminate\Session\TokenMismatchException;
});
// AJAX for ADD Member form, and for Delete Member
Route::get('members/dropdown', 'MemberController@getmembers');
// Get Member Delete info
Route::get('members/deleteinfo', 'MemberController@deleteinfo');
// AJAX for ADD Member form, and for Delete Member 
Route::get('relative/dropdown', 'MemberController@getmemberinfo');
//Route::post('preferrence/lang', 'PreferrenceController@lang');
Route::post('preferrence/lang', 'PreferrenceController@lang');
// Ajax for Find Member
Route::get('family/person/find','MemberController@find');
// Ajax for Relation Find 
// http://localhost/RelNet/public/family/findrelation?fid=30&rfid1=212&rfid2=217
Route::get('findrelation','MemberController@findrelation');
// member info box
Route::get('family/member/info','MemberController@memberinfo');



Route::get('fblogin', function(){
	return \Socialize::with('facebook')->redirect();
});
// public function redirectToProvider()
// {
//     return \Socialize::with('facebook')->redirect();
// }

Route::get('fb',function(){
	$user = \Socialize::with('facebook')->user();
	return redirect('/') ; 
	//$user->getName();
});
// public function handleProviderCallback()
// {
//     $user = \Socialize::with('facebook')->user();

//     // $user->token;
// }

// Route::get('/Error', function(){
// 	return view('Error');
// });


// Route::get('/test', 'TestController@index') ;
// Route::get('/test-family', 'TestController@family') ;


// Route::get('person/create','PersonController@create');
// Route::post('person', 'PersonController@store');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
