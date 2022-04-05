<?php

define('APPLICATION_ROOT', dirname(dirname(__FILE__)) . '/app/');
require(APPLICATION_ROOT . 'init.php');

route_request();


exit;
