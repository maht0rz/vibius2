<?php

session_start();
error_reporting(E_ALL); ini_set('display_errors', '1');

require'../vendor/autoload.php';
require '../app/settings/handlers.php';
require '../core/Framework.php';
require '../core/shutdown.php';

$framework->run();
