<?php declare(strict_types=1);
  require_once(__DIR__ . '/platform.php');
  require_once(__DIR__ . '/run-command.php');


// ...

$token = 'd';
output(runCommand(getContext(), getCommand()));
