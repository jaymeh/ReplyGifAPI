<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Goutte\Client;

class ImportController extends BaseController
{
    public function index() {
        // Load in Goutte Client
    	$client = new Client();
        $i = 0;

        // Setup blank array of images and current tags to match to each image
        $images = array();
        $current_tags = array();

        // Set while loop for 200 times. We probably won't get that high though
        while ($i < 200) {
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

                $images[] = str_replace('thumbnail', 'i', $image);
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
    }
}
