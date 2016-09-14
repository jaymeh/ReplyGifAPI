<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    /**
     * Gets a list of all terms 
     * @param  Request $request Lumen request object
     * @return json_array       JSON array of all terms
     */
    public function allTerms(Request $request) {
    	// Grab all terms and return them as a json array
    
   		// Option to get all lowercase options?
   		$lowercase = $request->input('lowercase');

   		$tags = Tag::all();

    	$tag_array = array();

    	// Loop through tags. Checks if lowercase is set and only get them if it is
    	foreach($tags as $tag) {
    		if($lowercase == true) {
    			$starts = $this->startsWithUpper($tag->tag_name);

    			if(!$starts) {
    				$tag_array[] = array('term' => $tag->tag_name);
    			}
    		} else {
    			$tag_array[] = array('term' => $tag->tag_name);
    		}
    	}

    	sort($tag_array);

    	return response()->json($tag_array);
    }

    public function termById(Request $request, $id) {
    	// Return error if we aren't given an id that can be parsed as an integer
    	if(!intval($id)) {
    		// Return an error
    		return response('Invalid id provided for term lookup', 500)->header('Content-Type', 'text/plain');
    	}

    	// Do the lookup with firstorfail.
    	$tag = 	Tag::where('id', $id)
    			->first();

    	if(!isset($tag->id)) {
    		return response('Could not find tag with given id: '.$id, 404)->header('Content-Type', 'text/plain');
    	}

    	$tag = array('term' => $tag->tag_name);

    	return response()->json($tag);

    	// Do the lookup with firstorfail.
    }

    public function gifByTermName($term_name) {
    	$item = Tag::find(4);

    	var_dump($item->tagAssignments);
    }

    protected function _termNameToId($termName) {
    	$term = Tag::where('tag_name', $termName);
    }

    /**
     * Detects if first character is uppercase or a number
     * @param  string $str String to check
     * @return bool        Returns true if first character is uppercase or a number
     */
    protected function startsWithUpper($str) {
	    $chr = mb_substr ($str, 0, 1, "UTF-8");

	    if(!intval($chr) <= 0) {
	    	return true;
	    }

	    return mb_strtolower($chr, "UTF-8") != $chr;
	}

    //
}
