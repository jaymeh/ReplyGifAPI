<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	protected $table = 'tags';

	protected $fillable = [];

	protected $dates = [];

	public static $rules = [
		// Validation rules
	];

	public function tagAssignments() {
		return $this->belongsToMany('App\Image', 'tag_assignments', 'tag_id', 'image_id');
	}

	// Relationships

}
