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
	public function images() {
		return $this->hasMany('App\Image', 'image_id');
	}

	public function tags() {
		return $this->hasMany('App\Tag', 'tag_id');
	}

}
