<?php

namespace Netflex\Customers\Traits\API;

/* use Exception; */

use Netflex\API;
use Netflex\Customers\Customer;

/* use Netflex\Commerce\Exceptions\CustomerNotFoundException; */

trait Customers
{
  public function save () {
    $payload = [];
    $client = API::getClient();

    if (!$this->id) {
      $this->attributes['id'] = $client
        ->post(trim(static::$base_path, '/'), $payload)
        ->customer_id;
    } else {
      if (count($this->modified)) {
        $client->put(trim(static::$base_path, '/') . '/' . $this->id, $payload);
      }
    }

    $this->refresh();
  }

  public function refresh () {
    $this->attributes = API::getClient()
      ->get(trim(static::$base_path, '/') . '/' . $this->id, true);

    return $this;
  }

  /**
   * @param string $email
   * @return static
   */
  public static function retrieveByEmail($email)
  {
    return new static(
      API::getClient()
        ->get(trim(static::$base_path, '/') . '/resolve/' . $code)
    );
  }

  /**
   * @param string $hash
   * @return static
   */
  public static function retrieveByHash($hash)
  {
    return new static(
      API::getClient()
        ->get(trim(static::$base_path, '/') . '/hash/' . $hash)
    );
  }

  /**
   * Creates empty order object based on orderData
   *
   * @param array $order
   * @return static
   */
  public static function create($signup = [])
  {
    return static::retrieve(
      API::getClient()
        ->post(trim(static::$base_path, '/'), $signup)
        ->signup_id
    );
  }
}
