<?php global $WOWTheme; ?>


<?php
	$slides=$WOWTheme->get_slides();
	if (!is_array($slides)||count($slides)==0) exit;
?>
	
	
<div class='slider-container'>
	<div class="slider">
			<div class="fp-slides">
				<?php foreach ($slides as $num=>$slide) { ?>
							<div class="fp-slides-item">
								<div class='slider-bgr'></div>
								<div class="fp-thumbnail">
									<?php if ($WOWTheme->get('slider', 'showthumbnail')) { ?>
									<a href="<?php echo $slide['link']?>" title=""><img src="<?php echo $slide['img']?>" alt="<?php echo $slide['ttl']?>" /></a>
									<?php } ?>
								</div>
								<?php if ($WOWTheme->get('slider', 'showtext')||$WOWTheme->get('slider', 'showttl')) { ?>
								<div class="fp-content-wrap">
									<div class="fp-content">
									
										<?php if ($WOWTheme->get('slider', 'showttl')) { ?>
										<h3 class="fp-title"><a href="<?php echo $slide['link']?>" title=""><?php echo $slide['ttl']?></a></h3>
										<?php } ?>
										
										<?php if ($WOWTheme->get('slider', 'showtext')) { ?>
										<p><?php echo $slide['content']?></p>
										<?php } ?>
										
										<?php if ($WOWTheme->get('slider', 'showhrefs')) { ?>
											<a class="fp-more" href="<?php echo $slide['link']?>"><?php echo $WOWTheme->_('readmore');?></a>
										<?php } ?>
										
									</div>
								</div>
								<?php } ?>
							</div>
				<?php } ?>
			</div>
			
			<div class="fp-prev-next-wrap">
				<a href="#fp-next" class="fp-next"></a>
				<a href="#fp-prev" class="fp-prev"></a>
			</div>
			
			
	</div>
</div>