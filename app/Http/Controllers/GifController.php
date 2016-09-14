<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;

class GifController extends Controller
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
    public function allGifs(Request $request) {
    	// Grab all gifs and return them as a json array
    
   		// Option to get all lowercase options?
   		// $lowercase = $request->input('lowercase');

   		$images = Image::all();

    	// Loop through tags. Checks if lowercase is set and only get them if it is
    	foreach($images as $image) {
    		$image_array[] = array('gif' => $image->image_link);	
    	}

    	sort($image_array);

    	return response()->json($image_array);
    }

    public function gifById(Request $request, $id) {
    	// Return error if we aren't given an id that can be parsed as an integer
    	if(!intval($id)) {
    		// Return an error
    		return response('Invalid id provided for term lookup', 500)->header('Content-Type', 'text/plain');
    	}

    	// Do the lookup with firstorfail.
    	$gif = 	Image::where('id', $id)
    			->first();

    	if(!isset($gif->id)) {
    		return response('Could not find gif with given id: '.$id, 404)->header('Content-Type', 'text/plain');
    	}

    	$gif = array('gif' => $gif->image_link);

    	return response()->json($gif);
    }
    //
}
