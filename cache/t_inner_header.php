<?php if(define('common', true)) exit('Access Denine');?><!DOCTYPE HTML>
<html lang="en" prefix="fb: http://www.facebook.com/2008/fbml" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Stylist</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" >
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="description" content="" />
<?php foreach($_OG as $key=>$og){?>
<meta property="<?php echo $key;?>" content="<?php echo $og;?>" />
<?php }?>
<base href="<?php echo $host_url;?>" />
<link rel="shortcut icon" href="favicon.png" />

<?php foreach($js_list as $js){?>
<script type="text/javascript" src="js/<?php echo $js;?>.js"></script>
<?php }?>
<?php foreach($css_list as $css){?>
<link href="css/<?php echo $css;?>.css" rel="stylesheet" type="text/css" />
<?php }?>

<script>
var scroll_size = '15';
var currenttime = Number("<?php echo $timenow;?>");
var curserver = '<?php echo $host_url;?>';
var regexp = {<?php foreach($regexp as $key=>$reg){?>'<?php echo $key;?>' : <?php echo $reg;?>,<?php }?>'dummy': ''},latest_scrolltop = 0;

$(document).ready(function(){

$(document).on("swiperight", function(e){
	if($.mobile.activePage.jqmData("panel") !== "open"){
		$("#nav-panel").panel("open");
	}
});

});



</script>

</head>

<body>