<?php 
	global $WOWTheme;
	
	get_header();
?>
			<h1 class="page-title"><?php
		
				   /* If this is a daily archive */ 
				   if (is_day()) { printf( $WOWTheme->_( 'dailyarchives' ), get_the_date() ); 
					
					/* If this is a monthly archive */ 
					} elseif (is_month()) { printf( $WOWTheme->_( 'monthlyarchives' ), get_the_date('F Y') );
					  
					/* If this is a yearly archive */ 
					} elseif (is_year()) { printf( $WOWTheme->_( 'yearlyarchives' ), get_the_date('Y') );
					
					/* If this is a general archive */ 
					} else { echo $WOWTheme->_( 'blogarchives' ); } 
			?></h1>
			<?php get_template_part('theloop'); ?>
			
			
<?php
	get_footer();
?>