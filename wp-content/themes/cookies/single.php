<?php 
	global $WOWTheme;
	
	get_header(); 
	
	?><div class="article-box"><?php
	
	get_template_part('theloop');
			
	the_tags("<div class='tags'><span>".$WOWTheme->_( 'tags' ).":&nbsp;&nbsp;</span>", ", ","</div>");
			
	get_template_part('relatedposts');
			
	comments_template();
			
	?></div><?php
	
	get_footer();
?>
