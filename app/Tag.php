<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	protected $table = 'tags';

	protected $fillable = [];

	protected $dates = [];

	public static $rules = [
		// Validation rules
	];

	// Relationships

}
