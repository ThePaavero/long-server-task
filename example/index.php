<?php

require __DIR__ . '../lib/LongServerTask.php';

function slowFunction()
{
  sleep(rand(1, 50));
  return true;
}

$persistentTask = new LongServerTask();
$persistentTask->setTaskName('Some slow function');
$persistentTask->setTaskStarted();
slowFunction();
$ourExampleSlowTask->setTaskDone();

// -------

echo 'Task done...';
