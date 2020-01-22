<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Netflex\Customers\Customer;
use Netflex\API\Providers\APIServiceProvider;

// ####### Bootstrapping #######
use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;

Dotenv::create(__DIR__)->load();

$container = new Container;

$container['config'] = [
  'api.publicKey' => getenv('NETFLEX_PUBLIC_KEY'),
  'api.privateKey' => getenv('NETFLEX_PRIVATE_KEY'),
];

(new APIServiceProvider($container))->register();

Facade::setFacadeApplication($container);

// ####### Testcode #######
$customer = Customer::find(119);

dd($customer);
