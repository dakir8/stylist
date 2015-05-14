<?php
require_once 'common.php';

//////////////////////////////////////////////////////////////////////////////////////////////

include_once 'test_db_structure.php';
DB::create('salon_cat')->drop();
DB::create('salon_service')->drop();
DB::create('salon')->drop();
DB::create('news')->drop();
DB::create('hair')->drop();
DB::create('stylist')->drop();
DB::create('comment')->drop();

foreach($salon_cat as $key=>$cat){
	DB::create('salon_cat')->save($cat);
}
foreach($salon_service as $key=>$service){
	DB::create('salon_service')->save($service);
}
foreach($salon as $key=>$s){
	DB::create('salon')->save($s);
}

exit('Done Init DB');