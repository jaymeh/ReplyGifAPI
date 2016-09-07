<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use App\Image;
use App\Tag;
use App\TagAssignment;

class ImportController extends Controller
{
	/**
	 * Points to the index action of the Import Controller
	 * @param  Request $request Lumen Request Object
	 */
    public function index(Request $request) {
        // Load in Goutte Client
    	$client = new Client();

        // Setup variables for loop
        $i = 0;
        $pages = 200;

        // Setup blank array of images and current tags to match to each image
        $images = array();
        $current_tags = array();

        // Set while loop for 200 times. We probably won't get that high though
        while ($i < $pages) {
            // Load in the crawler, pick out the tags and images
        	$crawler = $client->request('GET', 'http://replygif.net?page='.$i);

            // Grab all tags for images
            $image_tags = $crawler->filter('.item-list li .tags')->each(function ($node, $i) {
                return  $node->text();
            });

            // Add them to our tag list
            $current_tags = array_merge($current_tags, $image_tags);

            // Loop through the tags and load images attached
            foreach($image_tags as $tag) {
                $image = $crawler->selectImage($tag)->image()->getUri();

                $images[] = array('link' => str_replace('thumbnail', 'i', $image), 'tag' => $tag);
            }

            // Used to detect when we are at the last page
            $page_last = $crawler->filter('.pager .last')->each(function($node, $i) {
                return $node->text();
            });

            // Bail out of while loop when we are on the last page
            if(intval($page_last[0]) == $i && $i !== 0) {
                break;
            }

            $i++;
        }

        // Loop through images and store links and tags in database
        foreach($images as $image) {
        	$tag_ids = array();
            $gif_id = $this->storeLink($image['link']);

        	$tag_ids = $this->storeTerms($image['tag']);

        	$assignment_ids = $this->storeAssignments($tag_ids, $gif_id);
        }

        // Send a reponse saying that its been done :)
    }

    /**
     * Saves a single Image Link to the database
     * @param  string $image_link Link to the gif
     */
    protected function storeLink($image_link) {
    	$image_exists = Image::where('image_link', '=', $image_link)->exists();
    	
        if (!$image_exists) {
            // Check if image link exists.
            $image_data = new Image();

            $image_data->image_link = $image_link;

            $image_data->save();

            $id = $image_data->id;
            return $id;
        } else {
        	$image_object = Image::where('image_link', '=', $image_link)->first();

        	$id = $image_object->id;
        	return $id;
        }        
    }

    /**
     * Stores an array of tag items
     * @param  array $tag_items Array of tag items
     * @return array            Returns an array of tag ids from the database
     */
    protected function storeTerms($tag_items) {
    	// Break up the term ids, explode on comma
    	// 
    	// Loop through each one of them if more than one and enter it into the db
    	// 
    	// Grab the id as it comes out and return an array of them
    	// 
    	// 
    	$tag_ids = array();

    	$tag_array = explode(',', $tag_items);

    	foreach($tag_array as $tag) {
    		$tag = trim($tag);

    		$tag_exists = Tag::where('tag_name', '=', $tag)->exists();

    		if(!$tag_exists) {
    			$tag_data = new Tag();

    			$tag_data->tag_name = $tag;

    			$tag_data->save();

    			$tag_ids[] = $tag_data->id;
    		} else {
    			$tag_object = Tag::where('tag_name', '=', $tag)->first();

    			$tag_ids[] = $tag_object->id;
    		}
    	}

    	return $tag_ids;
    }

    /**
     * Stores the assignment data to link tags to gifs
     * @param  array 	$tag_ids	Contains an array of tag ids
     * @param  integer 	$image_id 	Contains an id of the image
     * @return array           	   	Returns an Array of ids for the assignments
     */
    protected function storeAssignments($tag_ids, $image_id) {
    	// Setup container for assignment ids
    	$item_ids = array();

    	// Check tags are an array
    	if(is_array($tag_ids)) {
    		// Loop through the tag ids
    		foreach($tag_ids as $tag_id) {
    			// Check that the assignment exists?
    			$tag_selector = TagAssignment::where('tag_id', '=', $tag_id)->where('image_id', '=', $image_id);

    			$item_exists = $tag_selector->exists();

    			// Check if the item exists. If it doesn't create it if it does just send its id back
    			if(!$item_exists) {
    				$assignment_data = new TagAssignment();

    				$assignment_data->image_id = $image_id;

    				$assignment_data->tag_id = $tag_id;

    				$assignment_data->save();

    				$item_ids[] = $assignment_data->id;
    			} else {
    				$item = $tag_selector->first();

    				$item_ids[] = $item->id;
    			}
    		}
    	}

    	return $item_ids;
    }
}
