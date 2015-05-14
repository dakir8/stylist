<?php
require_once('common.php');
$news_id = $_GET['id'] ? int($_GET['id']) : 0;
if($news_id){
	//get_news
	include template('news_detail');
}

include template('news');
?>