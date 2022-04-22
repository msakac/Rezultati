<?php
//use DB\SQL;
require_once("vendor/autoload.php");

$f3 = Base::instance(); //prezentira fat free
$f3->config('config.ini');
$f3->config('routes.ini');

//new Session();

$f3->run();
