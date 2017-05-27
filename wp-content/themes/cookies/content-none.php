<?php
/**
 * The template for displaying a "No posts found" message
 *
 * @package WordPress
 *
 */
 global $WOWTheme;
?>

<div class="article-box">

	<header class="page-header">
		<h1 class="page-title"><?php echo $WOWTheme->_( 'nothingfound' ); ?></h1>
	</header>

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

		<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'twentyfourteen' ), admin_url( 'post-new.php' ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

		<p><?php echo $WOWTheme->_( 'nosearchresults' ); ?></p>
		<?php get_search_form(); ?>

		<?php else : ?>

		<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.' ); ?></p>
		<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .page-content -->

</div><!-- .article-box -->