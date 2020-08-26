<?php

namespace Netflex\Customers;

use Exception;
use Netflex\API\Facades\API;
use Illuminate\Contracts\Auth\Authenticatable;
use Netflex\Query\QueryableModel as Model;

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
 * @property string $email
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
 * @property-read array $channels
 **/
class Customer extends Model implements Authenticatable
{
  protected $relation = 'customer';

  protected $relationId = null;

  protected $resolvableField = 'mail';

  /**
   * @var string|null Defines which (if any) field should be used to perform token based authentication
   * */
  protected $tokenField = null;

  /**
   * Retrieves a record by key
   *
   * @param int|null $relationId
   * @param mixed $key
   * @return array|null
   */
  protected function performRetrieveRequest(?int $relationId = null, $key)
  {
    return API::get('relations/customers/customer/' . $key, true);
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
    $response = API::post('relations/customers/customer', $attributes);

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
    return API::put('relations/customers/customer/' . $key, $attributes);
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

  /**
   * Attempts to authenticate with the given credentials.
   * If authenticate succeeds, we return the Customer instance
   *
   * @param array $credentials
   * @return static|null
   */
  public static function authenticate($credentials)
  {
    if (count($credentials) && array_key_exists('api_token', $credentials)) {
      $tokenField = with(new static)->tokenField;

      if ($tokenField !== null) {
        try {
          return static::where($tokenField, $credentials['api_token'])->first();
        } catch (Exception $e) {
          return;
        }
      }
    }

    $emailOrUsername = $credentials['email'] ?? $credentials['mail'] ?? $credentials['username'] ?? null;
    $field = (array_key_exists('email', $credentials) || array_key_exists('mail', $credentials)) ? 'mail' : (array_key_exists('username', $credentials) ? 'username' : null);
    $group = $credentials['group'] ?? null;

    try {
      $response = API::post('relations/customers/auth', [
        'username' => $emailOrUsername,
        'password' => $credentials['password'] ?? null,
        'field' => $field,
        'group' => $group
      ]);

      if ($response->authenticated) {
        return static::find($response->passed->customer_id);
      }
    } catch (Exception $e) {
      return;
    }
  }

  /**
   * Alias for mail field
   *
   * @return string
   */
  public function getEmailAttribute()
  {
    return $this->mail;
  }

  /**
   * @return array
   */
  public function getChannelsAttribute()
  {
    $channels = [];

    if (!$this->no_newsletter) {
      $channels[] = 'mail';
    }

    if (!$this->no_sms) {
      $channels[] = 'sms';
    }

    return $channels;
  }
}
