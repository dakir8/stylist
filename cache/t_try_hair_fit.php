<?php if(define('common', true)) exit('Access Denine');?><?php include template("include_header",true);?>
<div role="main" data-role="content">
	<p class='topic'>預覽髮型</p>
	<div>
		<img src="image/try_hair_fit.PNG" style="width:100%">
		<div align="center">
		<form action="index.php">
    		<input type="submit" value="保存圖片">
		</form>
	</div>
	</div>
	<div class="clear"></div>
</div>
<?php include template("include_footer",true);?>