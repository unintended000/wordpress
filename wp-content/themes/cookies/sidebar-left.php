<?php global $WOWTheme; ?>
	
	<?php if ( in_array($WOWTheme->layout, array(4,3,6) ) ) $style=" style='float:left'"; else $style='';?>
	
	<div class='sidebar left clearfix'<?php echo $style;?>>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Left Sidebar") ) { ?>
			Left sidebar
		<?php } ?>
	</div>