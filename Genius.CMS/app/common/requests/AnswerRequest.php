<?php

namespace App\Common\Requests;

use App\Core\View\Request;
use App\Core\Facades\Translate;
use App\Core\Http\{Status, Redirect};
use App\Core\Auth\{Account, User};
use App\Core\Data\Encryption;
use App\Core\Utils\Cast;
use Illuminate\Support\Str;

/**
 * Action triggered during registration.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class AnswerRequest extends Request implements \App\Core\Schema\Request
{
  private const ANSWER_YES = 1;

  private const ANSWER_NO = 2;

  private const ANSWER_DONTKNOW = 3;

  private int $answer = 0;

  public function getAction(): string
  {
    return 'Answer';
  }

  public function process(): void
  {
    $this->isSet([
      'system_id',
      'system_uuid',
      'system_session'
    ]);

    $this->isEmpty([
      'system_id',
      'system_uuid',
      'system_session'
    ]);

    $this->validate([
      ['system_id', FILTER_VALIDATE_INT],
      ['system_uuid', FILTER_SANITIZE_STRING],
      ['system_session', FILTER_UNSAFE_RAW]
    ]);

    if (isset($_POST['submit_yes'])) {
      $answer = self::ANSWER_YES;
    } elseif (isset($_POST['submit_no'])) {
      $answer = self::ANSWER_NO;
    } else {
      $answer = self::ANSWER_DONTKNOW;
    }

    $sessionData = json_decode($this->getData('system_session'), true);

    if (!is_array($sessionData)) {
      $sessionData = [];
    }

    $sessionData['last_answer'] = $answer;
    $sessionData['answer_user'] = Account::current()->getId();
    $sessionData['answer_time'] = time();
    $sessionData['answer_system'] = $this->getData('system_id');
    $sessionData['answer_system_uuid'] = $this->getData('system_uuid');

    $this->addContent('fields', ['product_name', 'product_description']);
    $this->addContent('message', json_encode($sessionData, JSON_UNESCAPED_UNICODE));

    $this->addContent('session', $sessionData);
    $this->addContent('sessionData', json_encode($sessionData, JSON_UNESCAPED_UNICODE));

    $this->finish(self::CODE_SUCCESS, Status::OK);
  }
}
