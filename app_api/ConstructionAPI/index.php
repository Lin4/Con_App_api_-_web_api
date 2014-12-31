<?php
/**
 * @name         Main API endpoint
 * @version      1.0
 * @author       Akila Perera <ravihansa3000@gmail.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.17
 */

// Slim Framework and register its PSR-0 autoloader.
require 'Slim/Slim.php';

// Include DB tools
require 'DatabaseTools.php';

// Sync logic
require 'SyncController.php';

// Print API version
function apiVersion($app, &$response) {
	$response ['message'] = array (
			'description' => "Construction API v1.0 is active!",
			'status' => "success" 
	);
}

\Slim\Slim::registerAutoloader ();

// instantiates a Slim application
$app = new \Slim\Slim ();

// GET synchronization pull all route
$app->get ( '/api/v1.0/sync/pull/', function () use($app) {
	$response = array ();
	if (syncPull ( $app, $response )) {
		$response ['message'] = array (
				'status' => "success" 
		);
	} else {
		$response ['message'] = array (
				'status' => "failed" 
		);
	}
	echo toJson ( $response );
	$app->status ( 200 );
} );

// GET synchronization pull images route
$app->post ( '/api/v1.0/sync/images/pull/', function () use($app) {
	$response = array ();
	
	if (syncPullImages ( $app, $response )) {
		$response ['message'] = array (
				'status' => "success" 
		);
	} else {
		$response ['message'] = array (
				'status' => "failed" 
		);
	}
	echo toJson ( $response );
	$app->status ( 200 );
} );

// POST synchronization push route
$app->post ( '/api/v1.0/sync/push/', function () use($app) {
	$response = array ();
	if (syncPush ( $app, $response )) {
		$response ['message'] = array (
				'status' => "success" 
		);
	} else {
		$response ['message'] = array (
				'status' => "failed" 
		);
	}
	echo toJson ( $response );
	$app->status ( 200 );
} );

// POST synchronization push images route
$app->post ( '/api/v1.0/sync/images/push/', function () use($app) {
	$response = array ();
	if (syncPushImage ( $app, $response )) {
		$response ['message'] = array (
				'status' => "success" 
		);
	} else {
		$response ['message'] = array (
				'status' => "failed" 
		);
	}
	echo toJson ( $response );
	$app->status ( 200 );
} );

// GET test route
$app->get ( '/api/v1.0/', function () use($app) {
	$response = array ();
	apiVersion ( $app, $response );
	echo toJson ( $response );
	$app->status ( 200 );
} );

// GET ROOT route
$app->get ( '/', function () use($app) {
	$response = array ();
	apiVersion ( $app, $response );
	echo toJson ( $response );
	$app->status ( 200 );
} );

// execute the Slim application and returns the HTTP response to the HTTP client.
$app->run ();