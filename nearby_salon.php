<?php
require_once('common.php');

$query = array(
);
$result_a = DB::create('salon')->find($query)->get();

include template('nearby_salon');
?>