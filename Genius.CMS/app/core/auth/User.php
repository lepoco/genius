<?php

namespace App\Core\Auth;

use App\Core\Utils\Cast;
use App\Core\Facades\DB;
use App\Core\Data\Encryption;
use App\Core\Http\Redirect;

/**
 * Represents the user.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class User extends \App\Core\Data\DatabaseObject
{
  private bool $active = false;

  private bool $confirmed = false;

  private int $role = 0;

  private string $uuid = '';

  private string $email = '';

  private string $name = '';

  private string $displayName = '';

  private string $image = '';

  private string $password = '';

  private string $cookieToken = '';

  private string $sessionToken = '';

  private string $language = '';

  private string $timezone = '';

  private string $createdAt = '';

  private string $lastLogin = '';

  private string $updatedAt = '';

  public function __construct(int $id = 0)
  {
    $this->id = $id;

    $this->fetch($id);
  }

  /**
   * Updates an entry in the database, does not change the password or tokens.
   */
  public function update(): bool
  {
    $success = DB::table('users')->where('id', $this->getId())->update([
      'display_name' => $this->getDisplayName(),
      'email' => $this->getEmail(),
      'role_id' => $this->getRole(),
      'image' => $this->getImage(),
      'language' => $this->getLanguage(),
      'timezone' => $this->getTimezone(),
      'is_active' => $this->isActive(),
      'is_confirmed' => $this->isConfirmed(),
      'updated_at' => date('Y-m-d H:i:s')
    ]);

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
      $password = Encryption::hash(
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
      $token = Encryption::hash($token, 'session');
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
      'time_last_login' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Updates all tokens and login information.
   */
  public function updateTokens(string $sessionToken, string $cookieToken, bool $keepSessionPlain = false, bool $keepCookiePlain = true): bool
  {
    if (!$keepSessionPlain) {
      $sessionToken = Encryption::hash($sessionToken, 'session');
    }

    return DB::table('users')->where('id', $this->getId())->update([
      'session_token' => $sessionToken,
      'cookie_token' => $cookieToken,
      'time_last_login' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Create a user instance from an associative array.
   */
  public static function build(array $properties): self
  {
    return (new self())
      ->setRole($properties['role'] ?? 1)
      ->setType($properties['type'] ?? 1)
      ->setUuid($properties['uuid'] ?? '')
      ->setEmail($properties['email'] ?? '')
      ->setDisplayName($properties['display_name'] ?? '')
      ->setImage($properties['image'] ?? '')
      ->setPassword($properties['password'] ?? '')
      ->setLanguage($properties['language'] ?? 'en_US')
      ->setTimezone($properties['timezone'] ?? 'UTC')
      ->setId($properties['id'] ?? 0);
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
    $this->language = $data->language ?? 'en_US';
    $this->timezone = $data->timezone ?? 'UTC';
    $this->lastLogin = $data->time_last_login ?? '';
    $this->createdAt = $data->created_at ?? '';
    $this->active = $data->is_active ?? true;
    $this->confirmed = $data->is_confirmed ?? false;
    $this->updatedAt = $data->updated_at ?? '';

    return true;
  }

  /**
   * Checks whether the object is a real user.
   */
  public function isValid(): bool
  {
    return $this->id > 0 && !empty($this->uuid);
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

  /**
   * Allows you to change the name if the user has not been registered yet.
   */
  public function setName(string $name): bool
  {
    if ($this->id > 1) {
      return false;
    }

    $this->name = trim($name);

    return false;
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
      return Redirect::url($this->image);
    }

    return $this->image;
  }

  public function setLanguage(string $language): self
  {
    $this->language = $language;

    return $this;
  }

  public function getLanguage(): string
  {
    return $this->language;
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
    return $this->updatedAt;
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
