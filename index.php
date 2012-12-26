<?php

/**
 * Working directory
 */
define('ROOTDIR',dirname(__FILE__));

/**
 * Bootstrap the system
 */
require_once('system/bootstrap.php');
Bootstrap::run();
Request::create()->execute()->send_headers()->send_body();

