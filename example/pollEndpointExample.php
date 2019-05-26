<?php

require __DIR__ . '/../lib/LongServerTask.php';

$persistentTask = new LongServerTask();
$persistentTask->setTaskName($_GET['taskName']);
$doneStatus = $persistentTask->taskIsDone();
echo json_encode([
  'done' => $doneStatus
]);
