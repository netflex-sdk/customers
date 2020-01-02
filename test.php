<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Netflex\API;
use Dotenv\Dotenv;

use Netflex\Customers\Customer;

Dotenv::create(__DIR__)->load();

API::setCredentials(
  getenv('NETFLEX_PUBLIC_KEY'),
  getenv('NETFLEX_PRIVATE_KEY'),
);

$customer = Customer::retrieve(119);

dd($customer);
dd($customer, json_encode($customer, JSON_PRETTY_PRINT));
