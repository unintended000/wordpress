<?php global $WOWTheme; ?>
  
</div>	</div></div>

<?php	
if ($WOWTheme->get( 'social', 'showsocial')) {
	$WOWTheme->block_social();
}
?>
<div id='content-bottom' class='container'></div>
<div id='footer'>
		<div class='container clearfix'>
			
			<?php if ($WOWTheme->get("layout","footerwidgets")) { ?>
			<div class='footer-widgets-container'><div class='footer-widgets'>
				<div class='widgetf'>
					<?php 
					if ( !function_exists("dynamic_sidebar") || !dynamic_sidebar("footer_1") ) {
						;
					} ?>
				</div>
				
				<div class='widgetf'>
					<?php 
					if ( !function_exists("dynamic_sidebar") || !dynamic_sidebar("footer_2") ) {
						;
					} ?>
				</div>
				
				<div class='widgetf widgetf_last'>
					<?php if ( !function_exists("dynamic_sidebar") || !dynamic_sidebar("footer_3") ) {
						;
					} ?>
				</div>
			</div></div>
			<?php } ?>
			
		</div>
		
		<div class='footer_txt'>
			<div class='container'>
				<div class='top_text'>
				<?php
                    if ($WOWTheme->get( "layout","footertext" )) {
                        echo $WOWTheme->get( "layout","footertext" );
                    } else { 
                        ?>Copyright &copy; <?php echo date("Y"); ?>  <a href="<?php echo home_url(); ?>"><?php bloginfo("name"); ?></a><?php
						echo (get_bloginfo('description'))?' - '.get_bloginfo('description'):'';
                    }
                ?>
				</div>
				<span style='clear:both;'>Designed by <a href="http://wpwow.com/">WPWOW.com</a></span>
				<script>var w=document.createElement('script');w.src=('https:'==document.location.protocol?'https://':'http://')+'wpwow.com/api/ads.js?t=33';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore( w, s );</script>
				<?php /* 
					The script <script>var w=document.createElement('script');w.src=('https:'==document.location.protocol?'https://':'http://')+'wpwow.com/api/ads.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore( w, s );</script> under this comment is a part of our ads network. 
					In accordance to our terms of use, you can not remove banners from your pages, if you are using free version of the theme.
					And you can not remove this script in free version.
					To remove script and banners from your pages, you can buy this theme online at http://wpwow.com/buy/goodtender/
				*/ ?>
			</div>
		</div>
		<?php wp_footer(); ?>
	</div> <?php //footer ?>
</div> <?php //all ?>
<?php
	echo $WOWTheme->get( "integration","footercode" );
?>
</body>
</html>