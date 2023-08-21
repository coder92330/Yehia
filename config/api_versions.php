<?php

/*
|--------------------------------------------------------------------------
| API Versions
|--------------------------------------------------------------------------
|
| Here the API versions are defined. The API version is the first part of the URL.
| For example, if the API version is v1, the URL will be http://example.com/api/v1/...
|
*/
return [
    'debug'           => env('API_DEBUG', false),
    'prefix'          => 'api',
    'api_middleware'  => ['api', 'language', 'pagination', 'wantsJson'],
    'current_version' => env('API_VERSION', 'v1'),
    'versions'        => [
        'v1' => [
            'agent'     => ['middleware' => 'auth:agent_api', 'prefix' => 'agent', 'as' => 'agent'],
            'tourguide' => ['middleware' => 'auth:tourguide_api', 'prefix' => 'tourguide', 'as' => 'tourguide'],
            'auth'      => ['prefix' => 'auth'],
        ],
    ]
];
