<?php
if(define('common', true))
exit('Access Denine');

$mime_types = array();
$mime_types['asx'] = 'video/x-ms-asf';
$mime_types['pdf'] = 'application/pdf';
$mime_types['bmp'] = 'image/bmp';
$mime_types['doc'] = 'application/msword';
$mime_types['eps'] = 'application/postscript';
$mime_types['gif'] = 'image/gif';
$mime_types['png'] = 'image/png';
$mime_types['htm'] = 'text/html';
$mime_types['html']= 'text/html';
$mime_types['jpe'] = 'image/jpeg';
$mime_types['jpeg']= 'image/jpeg';
$mime_types['jpg'] = 'image/jpeg';
$mime_types['js']  = 'application/x-javascript';
$mime_types['swf'] = 'application/x-shockwave-flash';
$mime_types['xls'] = 'application/vnd.ms-excel';
$mime_types['xlsx'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
$mime_types['apk'] = 'application/vnd.android.package-archive';

$regexp['username'] = '/^[0-9A-Za-z\u4e00-\u9fcc]{4,20}$/i';
$regexp['password'] = '/(.|\n){6,16}/';
$regexp['phone'] = '/[0-9]{8}/';
$regexp['quantity'] = '/^[0-9]{1,2}$/';
$regexp['email'] = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/';
$regexp['web'] = '/((http|https):\/\/)?[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/';

$referral = $_SERVER['HTTP_REFERER'];
$curl = $_SERVER['PHP_SELF'];
$_SERVER['HTTP_USER_AGENT'] = str_replace(';' , ',' , $_SERVER['HTTP_USER_AGENT']);
$useragent_str = $_SERVER['HTTP_USER_AGENT'];
$host_url = $server_name = ($_SERVER["HTTPS"] ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].'/stylist/';
$curpage = preg_replace('/\/(.*)/i','$1',$_SERVER['REQUEST_URI']);