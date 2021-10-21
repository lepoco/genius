<?php

namespace App\Core\Auth;

use App\Core\Auth\Billing;
use App\Core\Utils\Cast;
use App\Core\Facades\{DB, Option, Request};
use App\Core\Data\Encryption;

/**
 * Represents the user
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class User
{
  private bool $active = false;

  private bool $confirmed = false;

  private int $id = 0;

  private int $role = 0;

  private string $uuid = '';

  private string $email = '';

  private string $name = '';

  private string $displayName = '';

  private string $image = '';

  private string $password = '';

  private string $cookieToken = '';

  private string $sessionToken = '';

  private string $timezone = '';

  private string $createdAt = '';

  private string $lastLogin = '';

  private string $lastUpdate = '';

  private Billing $billing;

  public function __construct(int $id = 0)
  {
    $this->billing = new Billing($id);

    $this->fetch($id);
  }

  /**
   * Updates an entry in the database, does not change the password, tokens, or billing.
   */
  public function update(): bool
  {
    $success = DB::table('users')->where('id', $this->getId())->update([
      'display_name' => $this->getDisplayName(),
      'email' => $this->getEmail(),
      'role_id' => $this->getRole(),
      'image' => $this->getImage(),
      'timezone' => $this->getTimezone(),
      'is_active' => $this->isActive(),
      'is_confirmed' => $this->isConfirmed(),
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->billing->update();

    return $success;
  }

  /**
   * Checks whether the entered password matches the user's password.
   */
  public function comparePassword(string $password): bool
  {
    return Encryption::compare($password, $this->password, 'password', true);
  }

  /**
   * Updates the user's password in a database.
   */
  public function updatePassword(string $password, bool $plain = true): bool
  {
    if ($plain) {
      $password = Encryption::encrypt(
        $password,
        'password'
      );
    }

    return DB::table('users')->where('id', $this->getId())->update([
      'password' => $password
    ]);
  }

  /**
   * Checks whether the entered session token matches the user's token.
   */
  public function compareSessionToken(string $token): bool
  {
    return Encryption::compare($token, $this->sessionToken, 'session', true);
  }

  /**
   * Updates the user's token in a database.
   */
  public function updateSessionToken(string $token, bool $keepPlain = false): bool
  {
    if (!$keepPlain) {
      $token = Encryption::encrypt($token, 'session');
    }

    return DB::table('users')->where('id', $this->getId())->update([
      'session_token' => $token
    ]);
  }

  /**
   * Checks whether the entered cookie token matches the user's token.
   */
  public function compareCookieToken(string $token): bool
  {
    return $token === $this->cookieToken;
  }

  /**
   * Updates the user's token in a database.
   */
  public function updateCookieToken(string $token, bool $keepPlain = true): bool
  {
    return DB::table('users')->where('id', $this->getId())->update([
      'cookie_token' => $token
    ]);
  }

  /**
   * Updates the user's last login date.
   */
  public function updateLastLogin(): bool
  {
    return DB::table('users')->where('id', $this->getId())->update([
      'time_last_login' => date('Y-m-d H:i:s')
    ]);
  }

  public function updateTokens(string $sessionToken, string $cookieToken, bool $keepSessionPlain = false, bool $keepCookiePlain = true): bool
  {
    if (!$keepSessionPlain) {
      $sessionToken = Encryption::encrypt($sessionToken, 'session');
    }

    return DB::table('users')->where('id', $this->getId())->update([
      'session_token' => $sessionToken,
      'cookie_token' => $cookieToken,
      'time_last_login' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Create a user instance from an associative array.
   */
  public static function build(array $properties): self
  {
    $billing = Billing::build([
      'email' => $properties['email'] ?? ''
    ]);

    return (new self())
      ->setId($properties['id'] ?? 0)
      ->setRole($properties['role'] ?? 1)
      ->setType($properties['type'] ?? 1)
      ->setUuid($properties['uuid'] ?? '')
      ->setEmail($properties['email'] ?? '')
      ->setDisplayName($properties['display_name'] ?? '')
      ->setImage($properties['image'] ?? '')
      ->setPassword($properties['password'] ?? '')
      ->setTimezone($properties['timezone'] ?? 'UTC')
      ->setBilling($billing);
  }

  private function fetch(int $id): bool
  {
    if (0 === $id) {
      return false;
    }

    $data = DB::table('users')->where('id', $id)->first();

    if (empty($data)) {
      return false;
    }

    $this->id = $data->id ?? 0;
    $this->email = $data->email ?? '';
    $this->name = $data->name ?? '';
    $this->displayName = $data->display_name ?? '';
    $this->uuid = $data->uuid ?? '';
    $this->image = $data->image ?? '';
    $this->password = $data->password ?? '';
    $this->sessionToken = $data->session_token ?? '';
    $this->cookieToken = $data->cookie_token ?? '';
    $this->role = (int) $data->role_id ?? 1;
    $this->timezone = $data->timezone ?? '';
    $this->lastLogin = $data->time_last_login ?? '';
    $this->createdAt = $data->created_at ?? '';
    $this->updatedAt = $data->updated_at ?? '';

    return true;
  }


  public function isValid(): bool
  {
    return $this->id > 0 && ! empty($this->uuid);
  }

  public function markAsActive(): self
  {
    $this->active = true;

    return $this;
  }

  public function markAsConfirmed(): self
  {
    $this->confirmed = true;

    return $this;
  }

  /**
   * Just a shorter wrapper for \App\Core\Auth\User::getId()
   */
  public function id(): int
  {
    return $this->getId();
  }

  public function setId(int $id): self
  {
    $this->id = $id;

    return $this;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function setRole(int $role): self
  {
    $this->role = $role;

    return $this;
  }

  public function getRole(): int
  {
    return $this->role;
  }

  public function setType(int $type): self
  {
    $this->type = $type;

    return $this;
  }

  public function getType(): int
  {
    return $this->type;
  }

  public function setUUID(string $uuid): self
  {
    $this->uuid = $uuid;

    return $this;
  }

  public function getUUID(): string
  {
    return $this->uuid;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    $this->name = Cast::emailToUsername($email);

    return $this;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setDisplayName(string $displayName): self
  {
    $this->displayName = $displayName;

    return $this;
  }

  public function getDisplayName(): string
  {
    return $this->displayName;
  }

  public function setImage(string $image): self
  {
    $this->image = $image;

    return $this;
  }

  public function getImage(bool $http = false): string
  {
    if ($http) {
      return rtrim(Option::get('base_url', Request::root()), '/') . '/' . $this->image;
    }

    return $this->image;
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

  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }

  public function getLastLogin(): string
  {
    return $this->lastLogin;
  }

  public function getLastUpdate(): string
  {
    return $this->lastUpdate;
  }

  public function setBilling(Billing $billing): self
  {
    $this->billing = $billing;

    return $this;
  }

  public function getBilling(): Billing
  {
    return $this->billing;
  }

  private function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  public function isActive(): bool
  {
    return $this->active;
  }

  public function isConfirmed(): bool
  {
    return $this->confirmed;
  }
}
