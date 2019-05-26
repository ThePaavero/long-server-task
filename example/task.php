<?php

require __DIR__ . '/../lib/LongServerTask.php';

function slowFunction()
{
  sleep(rand(1, 10));
  return true;
}

$persistentTask = new LongServerTask();
$persistentTask->setTaskName($_GET['taskName']);
$persistentTask->setTaskStarted();
slowFunction();
$persistentTask->setTaskDone();
