<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\PersonForUserTrait;

class Person extends Model {

	use SoftDeletes;
	//use PersonForUserTrait ;

	protected $table = 'persons';
	protected $fillable = [
			'user_id',
			'family_id',
			'name',
			'nickname',
			'gender',
			'age',
			'siblingno',
			'image',
			'location',
			'deadoralive',
			'facebookid',
			'googleid',
			'email_id',
			'description'
	] ;

}
