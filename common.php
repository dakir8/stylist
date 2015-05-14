<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
//ini_set("session.gc_maxlifetime", 0);
//ini_set('session.cookie_lifetime', 0);
header('Content-type: text/html; charset=utf-8');

define('common', true);

$start = microtime();
$start = explode(' ',$start);
$startms = $start[1]+$start[0];

require 'include/config.inc.php';
require_once 'function/common.func.php';
require_once 'function/db.func.php';
//require_once 'function/dataio.func.php';

// ----------------------------------------------

DB::init('stylist');

// ----------------------------------------------

$js_list = array(
'jquery-1.11.3.min',
'jquery.mobile-1.4.5.min',
'common',
);
$css_list = array(
'themes/default/jquery.mobile-1.4.5.min',
'common'
);