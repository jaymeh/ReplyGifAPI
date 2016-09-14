<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {

	protected $table = 'images';

	protected $fillable = [];

	protected $dates = [];

	public static $rules = [
		// Validation rules
	];

	// Relationships
	public function tagAssignments() {
		return $this->belongsTo('App\TagAssignments', 'id');
	}

}
