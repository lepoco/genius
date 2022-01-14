<?php

namespace App\Core\Cron;

use DateTime;
use App\Core\Facades\DB;
use App\Core\View\Request;

/**
 * CRON job.
 *
 * @author  Pomianowski Leszek <pomian@student.ukw.edu.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
abstract class Job implements \App\Core\Schema\Job
{
  private array $dbData = [];

  abstract public function getName(): string;

  abstract public function getInterval(): string;

  abstract public function process(): void;

  public function __construct()
  {
    $this->getDbData();
  }

  /**
   * Gets a name of the job stored in the database.
   */
  final public function getJobName(): string
  {
    $name = strtolower($this->getName());
    $name = str_replace('job', '', $name);

    return trim($name) . '_job';
  }

  /**
   * Gets overall information about the job.
   */
  final public function getInfo(): array
  {
    return [
      'id' => $this->dbData['id'],
      'name' => $this->getName(),
      'job_name' => $this->getJobName(),
      'class_name' => get_class($this),
      'interval' => $this->getInterval(),
      'last_run' => $this->dbData['last_run'],
      'created_at' => $this->dbData['created_at'],
    ];
  }

  /**
   * Updates information stored in the database.
   */
  final public function update(): void
  {
    if ($this->dbData['id'] < 1) {
      DB::table('cron')->insertGetId([
        'name' => $this->getJobName(),
        'last_run' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s')
      ]);

      return;
    }

    DB::table('cron')->where('id', $this->dbData['id'])->update([
      'last_run' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Checks whether the selected job should be run.
   */
  final public function isTimePassed(): bool
  {
    if (empty($this->dbData['last_run'])) {
      return true;
    }

    if (empty($this->getInterval())) {
      return false;
    }

    $difference = $this->getElapsedMinutes($this->dbData['last_run']);
    $interval = $this->getIntervalInMinutes($this->getInterval());

    if ($interval < 1) {
      return false;
    }

    return $difference > $interval;
  }

  /**
   * Converts a text interval to minutes.
   */
  private function getIntervalInMinutes(string $interval): int
  {
    $interval = trim(strtolower($interval));
    $intervalParams = explode(' ', $interval);
    $intervalParams[0] = intval($intervalParams[0]);

    $intervalInMinutes = 0;

    if (str_contains($intervalParams[1], 'm')) {
      $intervalInMinutes = $intervalParams[0];
    } elseif (str_contains($intervalParams[1], 'h')) {
      $intervalInMinutes = $intervalParams[0] * 60;
    } elseif (str_contains($intervalParams[1], 'd')) {
      $intervalInMinutes = $intervalParams[0] * 60 * 24;
    } elseif (str_contains($intervalParams[1], 'w')) {
      $intervalInMinutes = $intervalParams[0] * 60 * 24 * 7;
    }

    if ($intervalInMinutes < 1) {
      $intervalInMinutes = 0;
    }

    return $intervalInMinutes;
  }

  /**
   * Checks how much time has elapsed since a given date.
   */
  private function getElapsedMinutes(string $time): int
  {
    $timeNow = new DateTime('now');
    $timeLastRun = new DateTime($time);

    $difference = abs(($timeNow->getTimestamp() - $timeLastRun->getTimestamp()) / 60);

    return (int) $difference;
  }

  private function getDbData(): void
  {
    $jobName = $this->getJobName();
    $query = DB::table('cron')->where(['name' => $jobName])->get('*')->first();

    if (!isset($query->last_run)) {
      $this->dbData = [
        'id' => 0,
        'name' => $jobName,
        'last_run' => '',
        'created_at' => ''
      ];

      return;
    }

    $this->dbData = [
      'id' => $query->id ?? 0,
      'name' => $jobName ?? '',
      'last_run' => $query->last_run ?? '',
      'created_at' => $query->created_at ?? ''
    ];
  }
}
