<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TagAssignment extends Model {

	protected $table = 'tag_assignments';

	protected $fillable = [];

	protected $dates = [];

	public static $rules = [
		// Validation rules
	];

	// Relationships

}
