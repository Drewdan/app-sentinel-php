<?php

return [
	'ingress_url' => env('SENTINEL_INGRESS_URL', ''),
	'application_id' => env('SENTINEL_APPLICATION_ID', ''),
	'key' => env('SENTINEL_KEY', ''),
	'user' => [
		'retrieve' => env('SENTINEL_LOG_USER', false),
		'model' => \App\Models\User::class,
		'identifier' => 'email',
	],
];
