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

    	// I thought about using first or fail however if we do it this way we take
        // the error handling into our hands which is always good.
    	$tag = 	Tag::where('id', $id)
    			->first();

        // Can't find the tag so we error out
    	if(!isset($tag->id)) {
    		return response('Could not find tag with given id: '.$id, 404)->header('Content-Type', 'text/plain');
    	}

        // Format tag how we should use it
    	$tag = array('term' => $tag->tag_name);

        // Send back json formatted response
    	return response()->json($tag);
    }

    /**
     * Get our GIF based on the term name we provide
     */
    public function gifByTermName(Request $request, $term_name) {
        // Decode our term name for spaces etc
        $term_name = urldecode($term_name);

        // Find term by name
        $term = Tag::where('tag_name', '=', $term_name)->first();

        // If we can't find the term error out
        if(!isset($term->id)) {
            return response('Could not find tag with given name: '.$term_name, 404)->header('Content-Type', 'text/plain');
        }

        // Get the images by the relationship
        $images = $term->tagAssignments;
        $image_links = array();

        // Loop through the images and get their links
        foreach($images as $image) {
            $image_links[] = $image->image_link;
        }

        // Generate a random key to use
        $random_link_key = array_rand($image_links);

        // Run the final image link through JSON Encode. This is because the json function
        // in the response object adds escape characters to slashes.
        $final_link = json_encode(array('image' => $image_links[$random_link_key]), JSON_UNESCAPED_SLASHES);

        // Return our 200 response
        return response($final_link, 200);
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
