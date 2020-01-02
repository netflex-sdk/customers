<?php

namespace Netflex\Customers;

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
 * */

class Customer extends ReactiveObject
{
  use CustomersAPI;
  use Retrievable;

  /** @var string */
  protected static $base_path = 'relations/customers/customer';

  protected $defaults = [
    'id' => null,
    'firstname' => null,
    'surname' => null,
  ];

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

}
