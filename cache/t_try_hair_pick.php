<?php if(define('common', true)) exit('Access Denine');?><?php include template("include_header",true);?>
<div role="main" data-role="content">
	<p class='topic'>預覽髮型</p>
	<div>
		<img src="image/try_hair_pick.PNG" style="width:100%">
	</div>
	<div align="center">
		<form action="try_hair_fit.php">
    		<input type="submit" value="確定">
		</form>
	</div>
	<div class="clear"></div>
</div>
<?php include template("include_footer",true);?>