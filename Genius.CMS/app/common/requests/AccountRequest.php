<?php

namespace App\Common\Requests;

use App\Core\Facades\{File, Translate};
use App\Core\View\Request;
use App\Core\Http\Status;
use App\Core\Utils\Path;
use App\Core\Files\Image;
use App\Core\Auth\{Account, User};
use Illuminate\Support\Str;

/**
 * Action triggered during account details change.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class AccountRequest extends Request implements \App\Core\Schema\Request
{
  private User $user;

  public function getAction(): string
  {
    return 'Account';
  }

  public function process(): void
  {
    $this->isSet([
      'id',
      'displayname',
      'language'
    ]);

    $this->isEmpty([
      'id',
      'displayname',
      'language'
    ]);

    $this->validate([
      ['id', FILTER_VALIDATE_INT],
      ['displayname', FILTER_SANITIZE_STRING],
      ['language', FILTER_SANITIZE_STRING]
    ]);

    if (empty(Account::current())) {
      $this->finish(self::ERROR_USER_INVALID, Status::UNAUTHORIZED);

      return;
    }

    if ($this->getData('id') < 1) {
      $this->finish(self::ERROR_USER_INVALID, Status::UNAUTHORIZED);

      return;
    }

    if ($this->getData('id') !== Account::current()->getId()) {
      $this->finish(self::ERROR_USER_INVALID, Status::UNAUTHORIZED);

      return;
    }

    $this->user = new User((int) $this->getData('id'));

    // Skip empty
    if (
        trim($this->getData('displayname')) == trim($this->user->getDisplayName())
        && trim($this->getData('language')) == trim($this->user->getLanguage())
        && !\App\Core\Facades\Request::hasFile('picture')
    ) {
      $this->addContent('message', Translate::string('But, You haven\'t made any changes...'));
      $this->finish(self::CODE_SUCCESS, Status::OK);

      return;
    }

    $this->processImage();

    $this->user->setDisplayName($this->getData('displayname'));

    switch ($this->getData('language')) {
      case 'pl_PL':
        $this->user->setLanguage('pl_PL');
        break;

      default:
        $this->user->setLanguage('en_US');
        break;
    }

    $this->user->update();

    $this->addContent('update', [
      [
        'selector' => '.editable__displayname',
        'type' => 'text',
        'value' => $this->user->getDisplayName()
      ],
      [
        'selector' => '.editable__picture',
        'type' => 'src',
        'value' => $this->user->getImage(true)
      ]
    ]);

    $this->addContent('message', Translate::string('Your account has been updated.'));
    $this->finish(self::CODE_SUCCESS, Status::OK);
  }

  private function processImage(): void
  {
    if (!\App\Core\Facades\Request::hasFile('picture')) {
      return;
    }

    /**
     * @var \Illuminate\Http\UploadedFile
     */
    $picture = \App\Core\Facades\Request::file('picture');

    if (!$picture->isValid()) {
      $this->addContent('fields', ['picture']);
      $this->addContent('message', Translate::string('Uploaded image is invalid.'));
      $this->finish(self::ERROR_FILE_INVALID, Status::UNAUTHORIZED);

      return;
    }

    if (!in_array($picture->getMimeType(), ['image/png', 'image/jpeg', 'image/jpg', 'image/webp', 'image/bmp'])) {
      $this->addContent('fields', ['picture']);
      $this->addContent('message', Translate::string('The uploaded image has the wrong format. Supported extensions for JPG, PNG, WEBp and BMP.'));
      $this->finish(self::ERROR_FILE_INVALID_MIME_TYPE, Status::UNAUTHORIZED);

      return;
    }

    if ($picture->getSize() > (1024 * 1024 * 3)) {
      $this->addContent('fields', ['picture']);
      $this->addContent('message', 'Uploaded image is too large.');
      $this->finish(self::ERROR_FILE_TOO_LARGE, Status::UNAUTHORIZED);

      return;
    }

    $userPath = Path::getAbsolutePath('public/img/profiles/' . $this->user->getUUID());
    $pictureName = time() . '-' . Str::random(64) . '.' . $picture->clientExtension();

    if (!File::isDirectory($userPath)) {
      File::makeDirectory($userPath, 0755, true);
    }

    $savedFile = $picture->move($userPath, $pictureName);

    /** @var FileException $savedFile */
    if (empty($savedFile)) {
      return;
    }

    // TODO: Scaling images
    //$file = Image::scale($userPath . '/' . $pictureName, 300, 300);

    // ray([
    //   'original' => $savedFile,
    //   'scaled' => $file
    // ]);

    $this->user->setImage('img/profiles/' . $this->user->getUUID() . '/' . $pictureName);
  }
}
