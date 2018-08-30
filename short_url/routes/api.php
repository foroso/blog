<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/25
 * Time: 11:14
 */






$router->get("short", [
    'as' => 'saverdi', 'uses' => 'IndexController@saveRdi',
]);




$router->post("short", [
    'as' => 'saverdi', 'uses' => 'IndexController@saveRdi',
]);


$router->get('{key}', [
    'as' => 'showrdi', 'uses' => 'IndexController@showRdi',
]);

//$api->version('v1', [
////    'namespace' => 'App\Http\Controllers\Api\V1',
//    // 'middleware' => ['b64']
//], function ($api) {
//
////    $api->get('/{key}', [function($key) {
////        dd($key);exit;
////    }]);
//
//});