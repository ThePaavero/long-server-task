<?php

class LongServerTask
{
  private $taskName;
  private $taskFilePath;
  private $taskStatusStoragePath;

  public function __construct($doSanityChecks = true)
  {
    $this->taskStatusStoragePath = __DIR__ . '/LongServerTask_tasks/';

    // Sanity checks?
    if ($doSanityChecks && ! $this->doSanityChecks())
    {
      return false;
    }

    return true;
  }

  public function setTaskName($taskName)
  {
    $this->taskName = $taskName;

    // This task's filename will the task string itself (as a filename safe string).
    $this->taskFilePath = $this->taskStatusStoragePath . $this->stringToSlug($this->taskName);
  }

  public function setTaskStarted()
  {
    // Create our file for this task.
    // Content doesn't matter, but let's write a timestamp. Maybe we'll want it later for performance
    // monitoring, timeouts, etc.
    file_put_contents($this->taskFilePath, time());
  }

  public function taskIsDone()
  {
    // If file exists, the related task still busy.
    file_put_contents('debug', $this->taskFilePath);
    return ! file_exists($this->taskFilePath);
  }

  public function setTaskDone()
  {
    // Simply unlink our task file.
    unlink($this->taskFilePath);
  }

  private function stringToSlug($string)
  {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
  }

  private function doSanityChecks()
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
