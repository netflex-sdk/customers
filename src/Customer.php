<?php

namespace Netflex\Customers;

use Netflex\API;
use Netflex\Support\Retrievable;
use Netflex\Support\ReactiveObject;
use Netflex\Customers\Traits\API\Customers as CustomersAPI;

/**
 * @property-read int $id
 * @property-read string $user_hash
 * @property string $extsync_id
 * @property int $group_id
 * @property string $firstname
 * @property string $surname
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
 * */

class Customer extends ReactiveObject
{
  use CustomersAPI;
  use Retrievable;

  /** @var string */
  protected static $base_path = 'relations/customers/customer';

  /** @var array */
  protected $defaults = [
    'id' => null,
    'firstname' => null,
    'surname' => null,
  ];

  /** @var array */
  protected $readOnlyAttributes = [
    'id', 'user_hash',
  ];

  /**
   * @param int $score
   * @return bool
   */
  public function getScoreAttribute($score)
  {
    return (int) $score;
  }

  /**
   * @param int $groupId
   * @return bool
   */
  public function getGroupIdAttribute($groupId)
  {
    return (int) $groupId;
  }

  /**
   * @param int $companyId
   * @return bool
   */
  public function getCompanyIdAttribute($companyId)
  {
    return (int) $companyId;
  }

  /**
   * @param int $no_newsletter
   * @return bool
   */
  public function getNoNewsletterAttribute($no_newsletter)
  {
    return (bool) $no_newsletter;
  }

  /**
   * @param int $no_sms
   * @return bool
   */
  public function getNoSmsAttribute($no_sms)
  {
    return (bool) $no_sms;
  }

  /**
   * @param int $no_sms
   * @return bool
   */
  public function getUseTimeAttribute($use_time)
  {
    return (bool) $use_time;
  }

  /**
   * @param int $has_error
   * @return bool
   */
  public function getHasErrorAttribute($has_error)
  {
    return (bool) $has_error;
  }

  /**
   * @param int $password_reset
   * @return bool
   */
  public function getPasswordResetAttribute($password_reset)
  {
    return (bool) $password_reset;
  }

  /**
   * @param object|array|null $segmentData
   * @return SegmentData
   */
  public function getSegmentDataAttribute($segmentData)
  {
    return SegmentData::factory($segmentData);
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
    $emailOrUsername = $credentials['email'] ?? $credentials['username'] ?? null;
    $field = $credentials['email'] ? 'mail' : ($credentials['username'] ? 'username' : null);
    $group = $credentials['group'] ?? null;

    $api = API::getClient();
    $response = $api->post('relations/customers/auth', [
      'username' => $emailOrUsername,
      'field' => $field,
      'group' => $group
    ]);

    if ($response->authenticated) {
      return static::retrieve($response->passed->customer_id);
    }
  }
}
