<?php

namespace Netflex\Customers\Traits\API;

/* use Exception; */

use Netflex\API;
use Netflex\Customers\Group;

/* use Netflex\Commerce\Exceptions\CustomerNotFoundException; */

trait Groups
{
  public function save () {
    $payload = [];
    $client = API::getClient();

    if (!$this->id) {
      $this->attributes['id'] = $client
        ->post(trim(static::$base_path, '/'), $payload)
        ->group_id;
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
   * Creates empty order object based on orderData
   *
   * @param array $order
   * @return static
   */
  public static function create($group = [])
  {
    return static::retrieve(
      API::getClient()
        ->post(trim(static::$base_path, '/'), $group)
        ->group_id
    );
  }
}
