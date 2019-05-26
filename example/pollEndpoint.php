<?php

require __DIR__ . '../lib/LongServerTask.php';

$ourExampleSlowTask = new LongServerTask([
  'name' => 'Some long task',
  'timeoutInMinutes' => 1,
]);

slowFunction();

$ourExampleSlowTask->announceTaskDone();

// -------

echo 'Task done...';
