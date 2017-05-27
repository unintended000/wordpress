<?php global $WOWTheme;
	if ( isset($_POST['ajaxpage'])&&$_POST['ajaxpage']=='1' ) {
		ob_start();
		get_template_part('theloop');
		get_template_part('navigation');
		$return['content']=ob_get_contents();
		ob_end_clean();
		header('Content-type: application/json');
		echo json_encode($return);
		die();
	}
	$WOWTheme->get_layout();
if (preg_match('/mobi/i', $_SERVER['HTTP_USER_AGENT'])) echo '<!DOCTYPE html>'."\r\n";
else echo '<!DOCTYPE html>'."\r\n";
?>
<html <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width" />
	
	<?php $WOWTheme->seo(); ?>

	<?php  wp_head(); ?>
	
	<style type="text/css"><?php echo $WOWTheme->get( 'integration','css' )?></style>
	
	<?php echo $WOWTheme->get( 'integration','headcode' ); ?>
	
	
</head>

<body <?php $class=$WOWTheme->block_slider_css(); $class.=' '.$WOWTheme->sidebars_type; body_class( $class ); ?> layout='<?php echo $WOWTheme->layout; ?>'>

<div id='scrollUp'><img src='<?php echo get_template_directory_uri().'/images/wow/arrow-up.png';?>' alt='Up' title='Scroll window up' /></div>
		
<div id='all'>
<div id='header'>
	
	<div class='container clearfix'>
	

			<div id="logo">
				<?php $WOWTheme->block_logo();?>
			</div>
			
			<div class="menusearch" title="">
				<?php get_search_form(); ?>
			</div>
			
			
			<div id='secondarymenu'>
				<?php wp_nav_menu('depth=0&theme_location=sec-menu&container_class=menu-topmenu-container&menu_class=menus menu-topmenu&fallback_cb=block_sec_menu');	?>
			</div>
			
			
			<div class="clear"></div>

		<?php wow_mobile_menu('sec-menu'); ?>
		<?php wow_mobile_menu('main-menu'); ?>
	
		
	</div>
		
			<div id='mainmenu-container'>
				<div id='mainmenu'>
					<?php $nav_menu_params=array(
						'depth'=>0,
						'theme_location'=>'main-menu',
						'menu_class'=>'menus menu-primary',
						'fallback_cb'=>'block_main_menu'
					);
					wp_nav_menu($nav_menu_params); ?>
				</div>
				<div class="clear"></div>
			</div>
			
			
			<?php
			if ((is_front_page()&&$WOWTheme->get( 'slider', 'homepage'))||(!is_front_page()&&$WOWTheme->get( 'slider', 'innerpage'))) {
				get_template_part( 'slider' );
			} ?>
			
		
	
</div>

<div id='content-top' class='container'></div>
<div id='content'>
	<div class='container clearfix'>
	<?php get_sidebar(); ?> 
		<div id="main_content">