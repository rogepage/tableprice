<?php
// header('Access-Control-Allow-Methods:  POST, GET, OPTIONS');
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

/** @var \Laravel\Lumen\Routing\Router $router */



$router->group(['prefix' => 'api'], function () use ($router) {
  
  $router->post('/member/create', 'MemberController@store');
  $router->post('/contract/create', 'ContractController@store');

  $router->post('/contract/make/payment', 'ContractController@makePaymentPrice');

  $router->post('/contract/renegotiate', 'ContractController@renegotiateContract');

  $router->post('/contract/client', 'ContractController@getContrats');



});

