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

  

  
  $router->get('/teste', 'TestController@testaQueue');
  


});

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {

 
  $router->post('/get_hash', 'ApiController@getHash');
  $router->post('/get_balance', 'ApiController@getBalance');
  $router->post('/get_transaction', 'ApiController@getTransaction');
  $router->post('/get_txid_transaction', 'ApiController@getTxidTransaction');
  $router->post('/send_balance', 'ApiController@sendBalance');
  $router->post('/get_balance_hash', 'ApiController@getBalanceHash');
  $router->post('/validate_hash', 'ApiController@validateHash');

  $router->post('/get_transaction_by_token', 'ApiController@getTransactionByToken');

  $router->post('/get_sum_transactions', 'ApiController@getSumTransactions');
  $router->post('/get_count_transactions', 'ApiController@getCountTransactions');
  $router->post('/get_sum_feenetwork', 'ApiController@getSumFeeNetwork');

  $router->post('/load_wallet', 'ApiController@loadWallet');

  // rotas de teste

  //$router->post('/get_sum_feenetwork', 'TestController@getSumFeeNetwork');
  //$router->post('/get_count_transactions', 'TestController@getCountTransactions');

  
  // rotas específicas para o app do dragonball

// temporário
   $router->post('/get_hash_dragon', 'DragonController@getHash');
   $router->post('/get_txid_dragon', 'DragonController@getTxidTransaction');
   $router->post('/get_balance_hash_dragon', 'DragonController@getBalanceHash');

   $router->post('/send_balance_dragon_member', 'DragonController@sendBalanceToMember');
   $router->post('/send_balance_dragon_partner', 'DragonController@sendBalanceToPartner');
  
  




});

$router->group(['prefix' => 'adm', 'middleware' => 'auth_adm'], function () use ($router) {
       $router->post('/get_payments', 'AdmController@payments');
       $router->post('/get_payment', 'AdmController@getPayment');
       $router->post('/authorize_payment', 'AdmController@authorizePayment');
       $router->post('/get_member_balance', 'AdmController@getBalance');
       

       
       
});

