<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/


    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],
	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'App\User',
		'secret' => '',
	],

	'google' => [
		//'client_id' => '',
	   	'client_id' => env('G_ID'),
	    'client_secret' => env('G_SECRET'),
	    'redirect' => env('G_REDIRECT'),
	],

	'twitter' => [
	    'client_id' => '',
	    //'client_id' => 'q2NR24fPB2VtayTOMa6NDAG9s',
	    'client_secret' => 'deLBI0nVkllV1aAOrohk0X9nDJY1tognRQO2myJsGis9GnmBCY',
	    'redirect' => 'http://sximobuilder.com/sximodemo/sximo5/user/twitter',
	],

	'facebook' => [
	    'client_id' => '',
	    //'client_id' => '725712687473196',
	    'client_secret' => '97af69633d9f00e4d3d2e9929574d9e9',
	    'redirect' => 'http://sximobuilder.com/sximodemo/sximo5/user/facebook',
	],		

];
