<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Goutte\Client;

class ImportController extends BaseController
{
    public function index() {
    	$client = new Client();

    	$html = \file_get_html('http://replygif.net');

    	foreach($html->find('img') as $element) {
    		echo $element->src . '<br>';
    	}

    	/* $crawler = $client->request('GET', 'http://replygif.net');

    	// var_dump($crawler->filter('li.views-row')->text());

    	//$link = $crawler->selectLink('Security Advisories')->link();

    	//$crawler = $client->click($link);

    	$crawler->filter('div.image-container')->each(function ($node) {
    		var_dump($node);
		    //print $node->text();
		});

		// var_dump($crawler->filter('li.views-row')); */
    }
}
