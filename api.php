<?php

require_once('autoload.php');

use Libs\CashMachine;
use Libs\Exceptions\InvalidArgumentException;
use Libs\Exceptions\NoteUnavailableException;

$value = isset($_GET['value']) ? $_GET['value'] : 0;

$atm = new CashMachine();

try {
	$output = $atm->withdraw($value);
}
catch(InvalidArgumentException $e) {
	$output = 'throw InvalidArgumentException';
}
catch(NoteUnavailableException $e) {
	$output = 'throw NoteUnavailableException';
}

echo json_encode($output);