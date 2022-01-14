<?php

namespace App\Core\Cron;

use DateTime;
use App\Core\Utils\Path;
use App\Core\Facades\{DB, Option};

/**
 * Responsible for cyclical tasks.
 *
 * @author  Pomianowski Leszek <pomian@student.ukw.edu.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Cron
{
  private const NAMESPACE = '\\App\\Common\\Cron\\';

  /**
   * Triggers a new instance of the class.
   */
  public static function run(): self
  {
    return (new self())->invokeJobs();
  }

  /**
   * If enabled, tries to trigger CRON jobs at the end of user action.
   */
  public static function runByUser(string|DateTime $lastRun): void
  {
    // TODO: Consider https://www.php.net/pthreads

    if (!empty($lastRun)) {
      if ($lastRun instanceof DateTime) {
        $timeLastRun = $lastRun->getTimestamp();
      } else {
        $timeLastRun = strtotime($lastRun);
      }

      $difference = (strtotime("-10 minutes") - $timeLastRun) / 60;

      if ($difference < 15) {
        return;
      }
    }

    Option::set('cron_last_run', new DateTime('now'));

    (new self())->invokeJobs();
  }

  /**
   * Retrieves a list and then tries to run jobs.
   */
  public function invokeJobs(): self
  {
    $jobLists = $this->getJobsClassList();

    foreach ($jobLists as $job) {
      $this->runJob($job);
    }

    Option::set('cron_last_run', new DateTime('now'));

    return $this;
  }

  /**
   * Gets list of information about every job.
   */
  public function getJobs(): array
  {
    $jobsClassList = $this->getJobsClassList();
    $jobsTable = [];

    foreach ($jobsClassList as $singleJobClass) {
      try {
        $jobInstance = new $singleJobClass();
      } catch (\Throwable $th) {
        continue;
      }

      $jobsTable[] = $jobInstance->getInfo();
    }

    return $jobsTable;
  }

  /**
   * Triggers a new job based on its class name.
   */
  private function runJob(string $jobClassName): bool
  {
    try {
      $jobInstance = new $jobClassName();
    } catch (\Throwable $th) {
      return false;
    }

    if (!$jobInstance->isTimePassed()) {
      return false;
    }

    $jobInstance->process();
    $jobInstance->update();

    return true;
  }

  /**
   * Gets a list of job classes.
   */
  private function getJobsClassList(): array
  {
    $files = array_diff(scandir(Path::getAppPath('common/cron/')), ['.', '..']);

    return array_map(function ($file) {
      if (str_ends_with($file, '.php')) {
        return self::NAMESPACE . str_replace('.php', '', $file);
      }
    }, $files);
  }
}
