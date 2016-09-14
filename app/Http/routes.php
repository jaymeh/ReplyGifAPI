<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
	// Return 404
    return $app->version();
});

$app->get('/import', 'ImportController@index');

/**
 * Routes for resource api-v1
 */
$app->group(['prefix' => 'api/v1','namespace' => 'App\Http\Controllers'], function($app)
{
    $app->get('term','TermController@allTerms');
  
    $app->get('term/{id}','TermController@termById');

    $app->get('image/{term_name}', 'TermController@gifByTermName');

    $app->get('gif', 'GifController@allGifs');

    $app->get('gif/{id}', 'GifController@gifById');
});
