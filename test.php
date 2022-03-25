<?php

use Taskforce\Task;

require_once "vendor/autoload.php";

$task1 = new Task(1,);
$task2 = new Task (2, 8);

assert($task1->getMapStatuses()['working'] == 'В работе');

assert($task1->getMapActions()['done'] == 'Выполнено');

assert($task1->getStatusByAction(Task::ACTION_CANCEL) == Task::STATUS_CANCELED);

assert($task1->getAllowedAction(Task::STATUS_NEW,Task::CUSTOMER) == Task::ACTION_CANCEL);

assert($task1->getAllowedAction(Task::STATUS_WORKING,Task::EXECUTOR) == Task::ACTION_REFUSED);

var_dump ($task1);

?>
