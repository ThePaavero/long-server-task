<?php

require __DIR__ . '/../lib/LongServerTask.php';

$persistentTask = new LongServerTask();
$persistentTask->setTaskName($_GET['taskName']);
$persistentTask->setTaskStarted();

echo json_encode([
  'success' => true
]);
