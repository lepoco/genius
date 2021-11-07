<?php

namespace App\Core\Auth;

use App\Core\Utils\Cast;
use App\Core\Facades\DB;
use App\Core\Data\Encryption;

/**
 * Represents an object with user billing information.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Billing extends \App\Core\Data\DatabaseObject
{
  private int $userId = 0;

  private string $firstName = '';

  private string $lastName = '';

  private string $street = '';

  private string $postal = '';

  private string $city = '';

  private string $country = '';

  private string $province = '';

  private string $phone = '';

  private string $email = '';

  private string $timezone = '';

  private string $createdAt = '';

  private string $updatedAt = '';

  public function __construct(int $id = 0)
  {
    $this->userId = $id;

    if (0 === $id) {
      return;
    }

    $this->fetch($id);
  }

  /**
   * Updates an entry in the database, does not change the password, tokens, or billing.
   */
  public function update(): bool
  {
    $data = DB::table('user_billings')->where('user_id', $this->getUserId())->first();

    if (empty($data)) {
      return $this->insertNew();
    }

    return DB::table('user_billings')->where('user_id', $this->getUserId())->update([
      'first_name' => $this->getFirstName(),
      'last_name' => $this->getLastName(),
      'street' => $this->getStreet(),
      'postal' => $this->getPostalCode(),
      'city' => $this->getCity(),
      'country' => $this->getCountry(),
      'province' => $this->getProvince(),
      'phone' => $this->getPhone(),
      'email' => $this->getEmail(),
      'updated_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Create a user instance from an associative array.
   */
  public static function build(array $properties): self
  {
    return (new self())
      ->setUserId($properties['user_id'] ?? 1)
      ->setFirstName($properties['first_name'] ?? '')
      ->setLastName($properties['last_name'] ?? '')
      ->setStreet($properties['street'] ?? '')
      ->setPostalCode($properties['postal_code'] ?? '')
      ->setCity($properties['city'] ?? '')
      ->setCountry($properties['country'] ?? '')
      ->setProvince($properties['province'] ?? '')
      ->setPhone($properties['phone'] ?? '')
      ->setEmail($properties['email'] ?? '')
      ->setTimezone($properties['timezone'] ?? 'UTC')
      ->setId($properties['id'] ?? 0);
  }

  private function insertNew(): bool
  {
    return (bool) DB::table('user_billings')->insert([
      'user_id' => $this->getUserId(),
      'first_name' => $this->getFirstName(),
      'last_name' => $this->getLastName(),
      'street' => $this->getStreet(),
      'postal' => $this->getPostalCode(),
      'city' => $this->getCity(),
      'country' => $this->getCountry(),
      'province' => $this->getProvince(),
      'phone' => $this->getPhone(),
      'email' => $this->getEmail()
    ]);
  }

  private function fetch(int $id): bool
  {
    if (0 === $id) {
      return false;
    }

    $data = DB::table('user_billings')->where('user_id', $id)->first();

    if (empty($data)) {
      return false;
    }

    $this->id = $data->id ?? 0;
    $this->userId = $data->user_id ?? 0;
    $this->firstName = $data->first_name ?? '';
    $this->lastName = $data->last_name ?? '';
    $this->street = $data->street ?? '';
    $this->postal = $data->postal ?? '';
    $this->city = $data->city ?? '';
    $this->country = $data->country ?? '';
    $this->province = $data->province ?? '';
    $this->phone = $data->phone ?? '';
    $this->email = $data->email ?? '';
    $this->timezone = $data->timezone ?? '';
    $this->createdAt = $data->created_at ?? '';
    $this->updatedAt = $data->updated_at ?? '';

    return true;
  }

  public function setUserId(int $id): self
  {
    $this->userId = $id;

    return $this;
  }

  public function getUserId(): int
  {
    return $this->userId;
  }

  public function setFirstName(string $firstName): self
  {
    $this->firstName = $firstName;

    return $this;
  }

  public function getFirstName(): string
  {
    return $this->firstName;
  }

  public function setLastName(string $lastName): self
  {
    $this->lastName = $lastName;

    return $this;
  }

  public function getLastName(): string
  {
    return $this->lastName;
  }

  public function setStreet(string $street): self
  {
    $this->street = $street;

    return $this;
  }

  public function getStreet(): string
  {
    return $this->street;
  }

  public function setPostalCode(string $postal): self
  {
    $this->postal = $postal;

    return $this;
  }

  public function getPostalCode(): string
  {
    return $this->postal;
  }

  public function setCity(string $city): self
  {
    $this->city = $city;

    return $this;
  }

  public function getCity(): string
  {
    return $this->city;
  }

  public function setCountry(string $country): self
  {
    $this->country = $country;

    return $this;
  }

  public function getCountry(): string
  {
    return $this->country;
  }

  public function setProvince(string $province): self
  {
    $this->province = $province;

    return $this;
  }

  public function getProvince(): string
  {
    return $this->province;
  }

  public function setPhone(string $phone): self
  {
    $this->phone = $phone;

    return $this;
  }

  public function getPhone(): string
  {
    return $this->phone;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function setTimezone(string $timezone): self
  {
    $this->timezone = $timezone;

    return $this;
  }

  public function getTimezone(): string
  {
    return $this->timezone;
  }

  public function getUpdatedAt(): string
  {
    return $this->updatedAt;
  }
}
