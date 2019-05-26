<?php

class LongServerTask
{
  private $taskName;
  private $taskFilePath;
  private $taskStatusStoragePath;

  /**
   * LongServerTask constructor.
   *
   * @param bool $doSanityChecks
   *   If you're sure you have directories and permissions etc. set up, set this to false. It'll be faster that way.
   */
  public function __construct($doSanityChecks = false)
  {
    $this->taskStatusStoragePath = __DIR__ . '/LongServerTask_tasks/';

    // Sanity checks?
    if ($doSanityChecks && ! $this->sanityChecksPass())
    {
      return false;
    }

    return true;
  }

  /**
   * Set our "Task name." This will determine our "lock file's" filename.
   *
   * @param string $taskName
   *   i.e. 'Calculate a shit ton of stuff'
   */
  public function setTaskName($taskName)
  {
    $this->taskName = $taskName;

    // This task's filename will the task string itself (as a filename safe string).
    $this->taskFilePath = $this->taskStatusStoragePath . $this->stringToSlug($this->taskName);
  }

  /**
   * Call this before you start your heavy lifting.
   */
  public function setTaskStarted()
  {
    // Create our file for this task.
    // Content doesn't matter, but let's write a timestamp. Maybe we'll want it later for performance
    // monitoring, timeouts, etc.
    file_put_contents($this->taskFilePath, time());
  }

  /**
   * Call to check if task is done or not.
   *
   * @return bool
   *   Is the task done?
   */
  public function taskIsDone()
  {
    // If file exists, the related task still busy.
    return ! file_exists($this->taskFilePath);
  }

  /**
   * Call this when you're done with your heavy lifting.
   */
  public function setTaskDone()
  {
    // Simply unlink our task file.
    unlink($this->taskFilePath);
  }

  /**
   * "Slugify" a string. Stolen from StackOverflow, I think.
   *
   * @param $string
   *   String to "slugify".
   * @return string
   *   "Slugified" version of the original string.
   */
  private function stringToSlug($string)
  {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
  }

  /**
   * Do some basic checks that ensure that we're able to do our thing.
   *
   * @return bool
   *   Can this script work?
   */
  private function sanityChecksPass()
  {
    // Make sure our storage path exists.
    if ( ! is_dir($this->taskStatusStoragePath))
    {
      // No? Create it.
      mkdir($this->taskStatusStoragePath);
    }

    // Make sure we can write to our directory.
    $testFilename = rand(1, 1000);
    $testFilepath = $this->taskStatusStoragePath . $testFilename;
    try
    {
      file_put_contents($testFilepath, 'Testing');
    } catch (Exception $e)
    {
      return false;
    }

    // Ok, we're good. Delete the dummy lock file (if we can write, there's no reason to suspect we can't unlink also).
    unlink($testFilepath);

    // Announce sanity checks passed.
    return true;
  }
}
