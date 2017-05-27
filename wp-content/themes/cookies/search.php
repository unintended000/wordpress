<?php 
	global $WOWTheme;
	
	get_header();
	
?>
			<h1 class='page-title'><?php echo sprintf($WOWTheme->_('searchresults'),get_search_query()); ?></h1>
			<?php
			
				if (!have_posts()) { ?>
					<p><?php echo $WOWTheme->_( 'nothingfound' )?></p>
				<?php }
				
				get_template_part('theloop'); 
				
			?>
			
		
<?php
	get_footer();
?>