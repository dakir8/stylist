<?php
require_once('common.php');

$result_a = array(
	array(
		'name' => 'no 1',
		'date' => '2015-06-01'
	),
	array(
		'name' => 'no 2',
		'date' => '2015-06-02'
	)
);

include template('my_booking');
?>