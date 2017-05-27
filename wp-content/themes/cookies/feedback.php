<?php
	/*Template Name: Contact form*/
?>
<?php global $WOWTheme;?>
<?php
	get_header();
?>
	<?php the_post(); ?>
<div id="post-<?php the_ID(); ?>" <?php post_class('article-box'); ?>>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			
			<div class="entry-content">
			<?php
			$feedback = get_post_meta($post->ID, 'feedback-options', true);
			if ( isset($_POST[$_SESSION['commentinput']])&&$_POST[$_SESSION['commentinput']]!='' ) {
				$msg='';
				$msg.='Name:'.$_POST['uName']."\r\n";
				$msg.='To Department:'.$_POST['uDepartment']."\r\n\r\n";
				$msg.=$_POST[$_SESSION['commentinput']];
				
				foreach( $feedback['department'] as $department ) {
					if ( $department['title']['value']!=$_POST['uDepartment'] ) continue;
					$email=$department['email']['value'];
				}
				
				if (!isset($email)||$email=='')
					$email=$feedback['email-for-feedbacks'];
				
				if (wp_mail( $email, $_POST['uSubject'], $msg, "From: ".$_POST['uEmail'] )) {
					echo '<p class="feedback-msg">'.$WOWTheme->_( 'emailok' ).'</p>';
				} else {
					echo '<p class="feedback-msg">'.$WOWTheme->_( 'emailfail' ).'</p>';
				}
				
			}
			the_content('');
		?>
		<div class="clear" style='height:40px;'></div>
		<?php
				if (is_array($feedback['department'])) { ?>
				<div class='departments'>
					<?php foreach( $feedback['department'] as $department ) { ?>
						<div class='department'>
							<h4><?php echo $department['title']['value']; ?></h4>
							<?php 
								foreach( $department as $key=>$detail ) { if ($key=='title'||$key=='email') continue;?>
									<p><span><?php echo $detail['name']; ?></span> <?php echo $detail['value']; ?></p>
								<?php } ?>
							<?php if (isset($department['email']['show'])&&$department['email']['show']==true) { ?><p><span><?php echo $WOWTheme->_( 'contactemail' ); ?>:</span> <?php echo $department['email']['value']; ?></p><?php } ?>
						</div>
					<?php } ?>
				</div>
				<?php } ?>
				
			<div class='contactform'>
			<form action='' method='POST'>
				<div class='uDetail'><span><?php echo $WOWTheme->_( 'contactname' ); ?>:</span><div class='input'><input type='text' name='uName' value='' /></div></div>
				<div class='uDetail'><span><?php echo $WOWTheme->_( 'contactemail' ); ?>:</span><div class='input'><input type='text' name='uEmail' value='' /></div></div>
				<div class='uDetail'><span><?php echo $WOWTheme->_( 'contactsubject' ); ?>:</span><div class='input'><input type='text' name='uSubject' value='' /></div></div>
				<?php if ( count($feedback['department']) > 1 ) { ?>
				<div class='uDetail'><span><?php echo $WOWTheme->_( 'contactdepartment' ); ?>:</span><div class='input'><select name='uDepartment'>
					<?php foreach( $feedback['department'] as $department ) { ?>
						<option value="<?php echo $department['title']['value']; ?>"><?php echo $department['title']['value']; ?></option>
					<?php } ?>
				</select></div></div>
				<?php } ?>
				<div class='uDetail'><span><?php echo $WOWTheme->_( 'contactmessage' ); ?>:</span><div class='input'><textarea name='uMessage' rows='8'></textarea></div></div>
				<input type='submit' class='readmore' value='Send' />
				<div class="clear"></div>
			</form>
			</div>
		</div>
	</div>
			<script>
	jQuery('textarea[name="uMessage"]').attr('name', '<?php echo $_SESSION['commentinput']; ?>');
</script>
<?php
	get_footer();
?>