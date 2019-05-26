<?php

require __DIR__ . '/../lib/LongServerTask.php';

$persistentTask = new LongServerTask();
$persistentTask->setTaskName($_GET['taskName']);

echo json_encode([
  'done' => $persistentTask->taskIsDone()
]);
