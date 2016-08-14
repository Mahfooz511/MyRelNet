<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\RelationForUserTrait;


class Relation extends Model {

	 use SoftDeletes;

	//use RelationForUserTrait ;

	protected $table = 'relations';
	protected $fillable = [
			'user_id',
			'family_id',
			'person_id',
			'relation',
			'relative_id'
	] ;
}
