<?php
require_once ("constants/inclusions.php");

session_start();
require_once ("constants/constants.php");

$start = new Controller();
$start->workflow();