<?php

use Taskforce\Task;

require_once "vendor/autoload.php";

$task1 = new Task(1, );
$task2 = new Task (2, 23);

assert($task1->getStatusByAction(Task::DoneAction) == Task::STATUS_DONE);

assert($task1->getAllowedAction(Task::STATUS_NEW,Task::CancelAction) == Task::ACTION_CANCEL);

?>
