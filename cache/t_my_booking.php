<?php if(define('common', true)) exit('Access Denine');?><?php include template("include_header",true);?>
<div role="main" data-role="content" data-demo-html="true">
				<p class="topic">我的預約</p>
				<ul data-role="listview" data-inset="true" class="ui-listview ui-listview-inset ui-corner-all ui-shadow">
					<?php foreach($result_a as $appointment){?>
					<li data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-li-has-thumb ui-first-child ui-btn-up-c"><div class="ui-btn-inner ui-li"><div class="ui-btn-text"><a href="index.php" class="ui-link-inherit">
						<img src="image/salon_default.jpg" class="ui-li-thumb">
						<h2 class="ui-li-heading"><?php echo $appointment['name'];?></h2>
						<p class="ui-li-desc"><?php echo $appointment['date'];?></p></a>
					</div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div>
					</li>
					<?php }?>
				</ul>
			</div>
<div class="clear"></div>
<?php include template("include_footer",true);?>