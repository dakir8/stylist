<?php if(define('common', true)) exit('Access Denine');?><?php include template("include_header",true);?>
<div role="main" data-role="content">
	<p class='topic'>預覽髮型</p>
	<div data-demo-html="true">
		<ul data-role="listview" class="ui-listview">
			<?php foreach($result_a as $collection){?>
			<li data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-first-child ui-btn-up-c"><div class="ui-btn-inner ui-li"><div class="ui-btn-text"><a href="try_hair_pick.php" style="display:block;" class="ui-link-inherit"><?php echo $collection;?></a></div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div></li>
			<?php }?>
		</ul>
	</div>
	<div class="clear"></div>
</div>
<?php include template("include_footer",true);?>