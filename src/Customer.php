<?php

namespace Netflex\Customers;

use Netflex\API;
use Illuminate\Contracts\Auth\Authenticatable;
use Netflex\Structure\Adapters\EloquentAdapter as Model;

/**
 * @property-read int $id
 * @property-read string $user_hash
 * @property string $extsync_id
 * @property int $group_id
 * @property string $firstname
 * @property string $surname
 * @property string $name
 * @property string $company
 * @property int $companyId
 * @property string $mail
 * @property string $phone
 * @property string $phone_countrycode
 * @property string $username
 * @property string $tags
 * @property string $created
 * @property string $updated
 * @property int $use_time
 * @property string $start
 * @property string $stop
 * @property bool $no_newsletter
 * @property bool $no_sms
 * @property bool $has_error
 * @property bool $password_reset
 * @property SegmentData[] $segmentData
 * @property GroupCollection[] $groups
 **/
class Customer extends Model implements Authenticatable
{
  protected $relation = 'customer';

  protected $relationId = null;

  protected $resolvableField = 'mail';

  /**
   * Retrieves a record by key
   *
   * @param int|null $relationId
   * @param mixed $key
   * @return array|null
   */
  protected function performRetrieveRequest(?int $relationId = null, $key)
  {
    return API::getClient()
      ->get('relations/customers/customer/' . $key, true);
  }

  /**
   * Inserts a new record, and returns its id
   *
   * @property int|null $relationId
   * @property array $attributes
   * @return mixed
   */
  protected function performInsertRequest(?int $relationId = null, array $attributes = [])
  {
    $response = API::getClient()
      ->post('relations/customers/customer', $attributes);

    return $response->customer_id;
  }

  /**
   * Updates a record
   *
   * @param int|null $relationId
   * @param mixed $key
   * @param array $attributes
   * @return void
   */
  protected function performUpdateRequest(?int $relationId = null, $key, $attributes = [])
  {
    return API::getClient()->put('relations/customers/customer/' . $key, $attributes);
  }

  /**
   * Deletes a record
   *
   * @param int|null $relationId
   * @param mixed $key
   * @return bool
   */
  protected function performDeleteRequest(?int $relationId = null, $key)
  {
    return false;
  }

  /**
   * Get the name of the unique identifier for the user.
   *
   * @return string
   */
  public function getAuthIdentifierName()
  {
    return 'id';
  }

  /**
   * Get the unique identifier for the user.
   *
   * @return mixed
   */
  public function getAuthIdentifier()
  {
    return $this->{$this->getAuthIdentifierName()};
  }

  /**
   * Get the password for the user.
   *
   * @return string
   */
  public function getAuthPassword()
  {
    return null;
  }

  /**
   * Get the token value for the "remember me" session.
   *
   * @return string
   */
  public function getRememberToken()
  {
    return $this->{$this->getRememberTokenName()};
  }

  /**
   * Set the token value for the "remember me" session.
   *
   * @param  string  $value
   * @return void
   */
  public function setRememberToken($value)
  {
    return;
  }

  /**
   * Get the column name for the "remember me" token.
   *
   * @return string
   */
  public function getRememberTokenName()
  {
    return 'token';
  }
}
