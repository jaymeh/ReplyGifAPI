<?php

namespace App\Http\Controllers;

use App\Tag;

class TermController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function allTerms() {
    	// Grab all terms and return them as a json array
    	$tags = Tag::all();

    	$tag_array = array();

    	foreach($tags as $tag) {
    		$tag_array[] = array('term' => $tag->tag_name);
    	}

    	sort($tag_array);

    	return response()->json($tag_array);
    }

    public function termById($id) {
    	// Grab a term by its id and return them as a json array
    }

    //
}
