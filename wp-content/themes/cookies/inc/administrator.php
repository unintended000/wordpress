<?php
if ( current_user_can('administrator')&&isset($_GET['restore_footer'])&&$_GET['restore_footer']=='1') {
	unlink(get_template_directory().'/bottom.php');
	copy(get_template_directory().'/inc/fbackup.txt',get_template_directory().'/bottom.php');
}
class AdminPage {
	var $PageOptions;
	var $tasks;
	var $updateready;
	function AdminPage() {
		global $WOWTheme, $pagenow;
		
		
		$this->tasks = array ('imageupload','formsave','zipupload','activate');
		$this->PageOptions=$WOWTheme->options;
		if (isset($_GET['page'])&&$_GET['page']!='')
			add_action('admin_head', array(&$this, 'loadHeadTemplate'));
		else {
			add_action('admin_head', array(&$this, 'loadFeedback'));
		}
		add_action('admin_menu', array(&$this, 'loadMenu'));
		add_action( 'admin_enqueue_scripts', array(&$this, 'wowEnqueueAdmin') );
		add_action('wp_ajax_processing_ajax', array(&$this, 'ajax_callback'));
		add_action('admin_init', 'wow_feedback_options', 1);
		add_action('save_post', 'wow_feedback_options_update', 0);
	}
	
	function wowEnqueueAdmin() {
		 wp_enqueue_script( 'wowAdmin', get_template_directory_uri().'/js/admin.js' );
		 wp_enqueue_media();
	}
	
	 function loadMenu(){
			$info = get_theme_data(TEMPLATEPATH.'/style.css');
			$name = $info['Name']?$info['Name']:'WOW Options';
		  add_menu_page('Theme', $name, 'manage_options', 'OptionsPage', array(&$this, 'ThemeOptionsPage'), '', 64);
		 add_theme_page( $name, $name, 'manage_options', 'OptionsPage', array(&$this, 'ThemeOptionsPage'));
		  $this->load_tabs_menu(1);
	}
	function loadFeedback() { ?>
		<link rel='stylesheet' href='<?php echo get_template_directory_uri()?>/inc/css/feedback.css' type='text/css' media='all' />
	<?php }
	  function loadHeadTemplate()
	{	
		if ($_GET['page']=='OptionsPage')$_GET['page']='general';
		if (is_array($this->PageOptions[$_GET['page']])) {
		?>
		<link rel='stylesheet' href='<?php echo get_template_directory_uri()?>/inc/css/admin.css' type='text/css' media='all' />
		<?php } ?>
		
		<script type="text/javascript">
			jQuery(function() {
				jQuery('ul.tabs-menu').delegate('li:not(.active)', 'click', function() {
					var s=jQuery(this).addClass('active').siblings().removeClass('active').parents('.tabs-inner').children('ul.tabs-content').children('li').hide().removeClass('active').eq(jQuery(this).index()).fadeIn('slow').addClass('active').attr('id');
					if (s=='updates') {
						if (jQuery("#updatesfrm").html()=='')
						jQuery("<iframe height='720px' width='100%' src='http://wpwow.com/updates/'></iframe>").appendTo("#updatesfrm");
					}
					if (s=='activate'||s=='updates'||s=='contacts') {
						jQuery('#wow-btns-float').hide();
						jQuery('.reset_data_btn').hide();
					} else {
						jQuery('#wow-btns-float').show();
						jQuery('.reset_data_btn').show();
					}
					jQuery('#adminmenu .wp-has-current-submenu ul li').removeClass('current').eq(jQuery(this).index()+1).addClass('current');
					jQuery('.reset_data_btn').text('Reset '+jQuery(this).text().trim()+' options');
				})
			})
			<?php if ($_GET['page']=='updates') { ?>
			jQuery(document).ready(function() {
				jQuery("<iframe height='720px' width='100%' src='http://wpwow.com/updates/'></iframe>").appendTo("#updatesfrm");
			});
			<?php } ?>
		</script>
<?php
	}
	
	
	function ThemeOptionsPage() {
		?>
        <div class="wrap">
			<?php if ($_GET['page']=='updates'||$_GET['page']=='activate'||$_GET['page']=='contacts') {
				$class=' style="display:none"';
			} else {
				$class='';
			} ?>
			
                <?php 
                        $info = get_theme_data(TEMPLATEPATH.'/style.css');
                        $ver = $info['Version']?$info['Version']:'';
						$name = $info['Name']?$info['Name']:'';
                    ?> 
           
         
                

                <div class="tabs">
					<div class='tabs-inner'>
						<ul class="tabs-menu">
							<?php
								$this->load_tabs_menu();
							?>
						</ul>
						<div class='wpwow-top'><img src="<?php echo get_template_directory_uri()?>/inc/images/logo.png" alt="wpwow.com" style='' />
								<h1><?php echo $name?> | <?php echo $ver?>
								</h1>
						</div>
						<ul class="tabs-content">
						<form></form>
							<?php
								$this->load_tabs_content();
							?>
						</ul>
						<div style='clear:both'></div>
						
					</div>
				</div>
                    
                            <div class='wow-btns'><div class='bottom-background'></div>
								<?php
									$_SESSION['reset']=rand();
								?>
								<form action='' method='POST' id='resetform'>
									<input type='hidden' name='reset' value='<?php echo $_SESSION['reset']?>' />
									<input type='hidden' name='option' value='' />
								</form>
								<img class='ajaxloader' src="<?php echo get_template_directory_uri()?>/inc/images/ajax-loader.gif" alt="Please wait" title="Please wait" />
								<img id='imgloader' src="<?php echo get_template_directory_uri()?>/inc/images/img-loader.gif" alt="Please wait" title="Please wait" style="display:none" /><span id='server_answer'></span>
								<span class='wow-button save_data_btn' title='Save Changes'>Save Changes</span>
								<a class="wow-button reset_data_btn">Reset <?php echo $this->PageOptions[$_GET['page']]['name'] ?> options</a>
                            </div>
                        
    
        </div>
    <?php
	}
	
	function load_tabs_menu($type=0) {
		
		if (is_array($this->PageOptions)&&count($this->PageOptions>0)) {
			
			foreach ($this->PageOptions as $href=>$menu) {
				if ($type) {
					add_submenu_page( 'OptionsPage', $menu['name'], $menu['name'], 'manage_options', $href, array(&$this,'ThemeOptionsPage'));
				} else {
					echo '<li class="'.((($_GET['page']==$href)||($_GET['page']=='OptionsPage'&&$href=='general'))?'active':'').'">
					<div class="icon" style="background-image:url('.get_template_directory_uri().'/inc/images/menu/'.$href.'.png)" alt="'.$menu['name'].'"></div>'.$menu['name'].'</li>';
				}
			}
			remove_submenu_page( 'OptionsPage', 'OptionsPage' );
		}
	}
	
	function load_tabs_content($type=0) {
		
		if (is_array($this->PageOptions)&&count($this->PageOptions>0)) {
			foreach ($this->PageOptions as $href=>$x) {
				echo '<li id="'.$href.'" '.((($_GET['page']==$href)||($_GET['page']=='OptionsPage'&&$href=='general'))?" style='display:block' class='content-li active'":' class="content-li"').'><h2>'.$x['name'].'</h2><div class="adm-form">';
				if ($href!='activate') echo '<form id="form_'.$href.'" method="POST">';
				echo "<input type='hidden' name='option' value='".$href."' />";
				foreach ($x['content'] as $param) {
					$param['option']=$href;
					$this->show_input( $param );
				}
				if ($href!='activate') echo '</form>';
				echo '<div class="clear"></div></div></li>';
			}
		}
	}
	
	function show_input($param){	
		global $WOWTheme;
		switch ($param['type']) {
						case 'p':
							?>
							<div class='item' style='font-style:italic;'>
								<?php echo $param['value']?>
							</div>
							<?php
						break;
						case 'sidebars':
							?>
							<div class='item'>
								<div class='p_ttl'>
								<?php if (isset($param['hint'])&&$param['hint']!='') { ?>
								<span class='hint' alt='<?php echo $param['hint']?>'><img src='<?php echo get_template_directory_uri()?>/inc/images/hint.png' /></span>
								<?php } ?>
								<span class='span'><?php echo $param['ttl']?></span><div class="separator"></div></div>
								<div class='sidebarselector'>
									<img src="<?php echo get_template_directory_uri()?>/inc/images/sidebar-no.png" alt="No Sidebars" title="No Sidebars" />
									<img src="<?php echo get_template_directory_uri()?>/inc/images/sidebar-r.png" alt="Right Sidebar" title="Right Sidebar" />
									<img src="<?php echo get_template_directory_uri()?>/inc/images/sidebar-l.png" alt="Left Sidebar" title="Left Sidebar" />
									<img src="<?php echo get_template_directory_uri()?>/inc/images/sidebar-lr.png" alt="Left and Right Sidebars" title="Left and Right Sidebars" />
									<img src="<?php echo get_template_directory_uri()?>/inc/images/sidebar-r2.png" alt="2 Right Sidebars" title="2 Right Sidebars" />
									<img src="<?php echo get_template_directory_uri()?>/inc/images/sidebar-l2.png" alt="2 Left Sidebars" title="2 Left Sidebars" />
									<select autocomplete='off' name='<?php echo $param['name']?>' class='tinput' id='list_<?php echo $param['name']?>'>
									<?php
										foreach ($param['params'] as $value=>$option) {
											?><option value='<?php echo $value?>'<?php echo ($param['value']==$value)?" selected='selected'":""?>><?php echo $option?></option><?php
										}
									?>
									</select>
									<script>
										jQuery('.sidebarselector img').eq(jQuery('.sidebarselector select option:selected').index()).addClass('active');
									</script>
								</div>
							</div>
							<?php
						break;
						case 'activator':
							?>
							<?php
								if ($handle=@fopen(TEMPLATEPATH."/license.txt", 'r')) {
									$txt=fread($handle, filesize(TEMPLATEPATH."/license.txt"));
									if ( preg_match('/Theme\sActivated:\s(.*)/', $txt, $matches) ) {
										?>
										<div class='item'>
											Theme was successfuly activated with key <?php echo $matches[1];?>
										</div>
										<?php
										break;
									}
								}
							?>
							<div class='item'>
								<?php
									$info=get_theme_data(TEMPLATEPATH.'/style.css');
									$themename=strtolower($info['Name']);
								?>
								<ul class='rightlinks'>
									<li><a href="http://wpwow.com/support/" target="_blank">Forum</a></li>
									<li><a href="http://wpwow.com/<?php echo $themename; ?>/" target="_blank">Theme's page</a></li>
									<li><a href="http://wpwow.com/terms/" target="_blank">Licence</a></li>
								</ul>
								You can simply remove links from the footer after purchase and activate the theme.<br />
							</div>
								<div class='item activation-purchase'>
										<?php
											$data[]='domain='.$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_NAME'];
											$data[]='info='.$info['Name'];
											$data[]='theme='.get_template_directory_uri();
											$wow_hash=md5(rand(0,mktime()));
											update_option('wow_hash',$wow_hash);
											$data[]='wow_hash='.$wow_hash;
											$data='?'.implode('&', $data);
										?>
									<iframe src='http://wpwow.com/dashboard/<?php echo $data; ?>' width='100%' height='100px' scrolling='no'>
									</iframe>
									
								</div>
								<div class='item activation-activate'>
									<div class='p_ttl'>
									
									<span class='hint' alt='<?php echo $param['hint']?>'><img src='<?php echo get_template_directory_uri()?>/inc/images/hint.png' /></span>
									
									
									<span class='span'>Activate theme</span><div class="separator"></div></div>
									
									
									<div id='activation-params' method='POST' action=''>
										<?php
											$data=array();
											$data['domain']=$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_NAME'];
											$data['info']=get_theme_data(TEMPLATEPATH.'/style.css');
											$data['info']=$data['info']['Name'];
											$data['theme']=get_template_directory_uri();
											$data['wow_hash']=$wow_hash;
											foreach ( $data as $key => $value ) {
												echo "<input type='hidden' name='".$key."' value='".$value."' />";
											}
										?>
										<input type='hidden' name='abs' value='<?php echo dirname(__FILE__); ?>' />
										<input class='tinput' id='act_key' type='text' name='act_key' value='' />
										<input type='button' class='activate wow-button' value='Activate'>
									</div>
									<center></center>
									
									<iframe height='500px' width='100%' src='' id='sActivator' onLoad='jQuery("#imgloader").hide();' style='margin-top:25px;'>
									</iframe>
								</div>
							<?php
						break;
						case 'updates':
							?>
							<div class='item'>
								<div class='p_ttl'>
								<?php if (isset($param['hint'])&&$param['hint']!='') { ?>
								<span class='hint' alt='<?php echo $param['hint']?>'><img src='<?php echo get_template_directory_uri()?>/inc/images/hint.png' /></span>
								<?php } ?>
								<span class='span'><?php echo $param['ttl']?></span><div class="separator"></div></div>
								<div id="updatesfrm"></div>
							</div>
							<?php
						break;
						case 'text':
							?>
							<div class='item'>
								<div class='p_ttl'>
								<?php if (isset($param['hint'])&&$param['hint']!='') { ?>
								<span class='hint' alt='<?php echo $param['hint']?>'><img src='<?php echo get_template_directory_uri()?>/inc/images/hint.png' /></span>
								<?php } ?>
								<span class='span'><?php echo $param['ttl']?></span><div class="separator"></div></div>
								<input autocomplete='off' class='tinput' type='text' name='<?php echo $param['name']?>' value="<?php echo htmlspecialchars($param['value']);?>" />
							</div>
							<?php
						break;
						case 'textarea':
							?>
							<div class='item'>
								<div class='p_ttl'>
								<?php if (isset($param['hint'])&&$param['hint']!='') { ?>
								<span class='hint' alt='<?php echo $param['hint']?>'><img src='<?php echo get_template_directory_uri()?>/inc/images/hint.png' /></span>
								<?php } ?>
								<span class='span'><?php echo $param['ttl']?></span><div class="separator"></div></div>
								<textarea autocomplete='off' class='tinput' name='<?php echo $param['name']?>'><?php echo $param['value']?></textarea>
							</div>
							<?php
						break;
						case 'file':
							?>
							<div class='item'>
								<div class='p_ttl'>
								<?php if (isset($param['hint'])&&$param['hint']!='') { ?>
								<span class='hint' alt='<?php echo $param['hint']?>'><img src='<?php echo get_template_directory_uri()?>/inc/images/hint.png' /></span>
								<?php } ?>
								<span class='span'><?php echo $param['ttl']?></span><div class="separator"></div></div>
								<img src='<?php echo $param['value']?>' alt='' id='up_<?php echo $param['name']?>_img' />
								<input autocomplete='off' class='tinput finput uploadinput' type='text' id="up_<?php echo $param['name']?>" name='<?php echo $param['name']?>' value='<?php echo $param['value']?>' />
								<span class="gc_imageupload wow-button" target="up_<?php echo $param['name']?>">Change</span>
							</div>
							<?php
						break;
						case 'check':
							?>
							<div class='item checkbox'>
								<div class='p_ttl'>
								<?php if (isset($param['hint'])&&$param['hint']!='') { ?>
								<span class='hint' alt='<?php echo $param['hint']?>'><img src='<?php echo get_template_directory_uri()?>/inc/images/hint.png' /></span>
								<?php } ?>
								<span class='span'><?php echo $param['ttl']?></span><div class="separator"></div></div>
								<span class='tcheck'><input type='checkbox' name='<?php echo $param['name']?>' value='1' <?php echo ($param['value'])?"checked='checked'":""?> /></span>
							</div>
							<?php
						break;
						case 'select':
							?>
							<div class='item'>
								<div class='p_ttl'>
								<?php if ($param['hint']!='') { ?>
								<span class='hint' alt='<?php echo $param['hint']?>'><img src='<?php echo get_template_directory_uri()?>/inc/images/hint.png' /></span>
								<?php } ?>
								<span class='span'><?php echo $param['ttl']?></span><div class="separator"></div></div>
								<select autocomplete='off' name='<?php echo $param['name']?>' class='tinput' id='list_<?php echo $param['name']?>'>
									<?php
										foreach ($param['params'] as $value=>$option) {
											?><option value='<?php echo $value?>'<?php echo ($param['value']==$value)?" selected='selected'":""?>><?php echo $option?></option><?php
										}
									?>
								</select>
							</div>
							<?php
						break;
						case 'variants':
							?>
							<div class='item'>
								<ul id='depended_<?php echo $param['depend']?>' class='variants'>
									<?php
										foreach($param['variants'] as $value=>$func) {
											if (is_callable(array(get_class($this), $func))) {
?>
<li id='variant_<?php echo $value?>' class='variant'<?php echo (($value==$WOWTheme->get($param['option'], $param['depend']))?" style='display:block'":"")?>>
<?php
												call_user_func(array( get_class($this), $func));
												echo "</li>";
											}
										}
									?>
								</ul>
								<script>
									jQuery(document).ready(function() {
										jQuery('#list_<?php echo $param['depend']?>').live('change', function() {
											jQuery('#depended_<?php echo $param['depend']?> li.variant').hide();
											jQuery('#depended_<?php echo $param['depend']?> #variant_'+jQuery(this).val()).show();
										});
									});
								</script>
							</div>
							<?php
						break;
						case 'group':
							?>
							<span class='group_ttl'><?php echo $param['ttl']?></span>
							<div class='group_box' alt='<?php echo $param['name']?>'>
								<?php
									foreach ($param['content'] as $key=>$value){
										$value['value']=$param['value'][$key];
										$value['name']=$param['name']."[".$value['name']."]";
										$this->show_input($value);
									}
								?>
							</div>
							<?php
						break;
						
						case 'socials':
							?>
							<div class='item'>
							<div class='p_ttl'>
								<?php if ($param['hint']!='') { ?>
								<span class='hint' alt='<?php echo $param['hint']?>'><img src='<?php echo get_template_directory_uri()?>/inc/images/hint.png' /></span>
								<?php } ?>
								<span class='span'><?php echo $param['ttl']?></span><div class="separator"></div></div>
								
							<div class='socialbox' alt='<?php if (isset($param['name'])) {echo $param['name']; }?>'>
								<table>
									<tr class='th'>
										<th style='width:24px'></th>
										<th style='width:40%'>Service</th>
										<th>Display</th>
									</tr>
									<?php
										foreach ($param['value'] as $key=>$detail) {
										?>
											<tr alt='<?php echo $key?>'>
											<td class='trdrag'>
												<input type='hidden' class='param-ttl' name='socials[<?php echo $key?>][ttl]' value='<?php echo $detail['ttl']?>' />
												<input type='hidden' class='param-code' name='socials[<?php echo $key?>][code]' value='<?php echo $detail['code']?>' />
											</td>
											<td style='width:50%' class='displ-ttl'>
												<?php echo $detail['ttl']?>
											</td>
											<td>
												<span class='tcheck'><input type='checkbox' name='socials[<?php echo $key?>][show]' value='1' <?php echo ($detail['show'])?' checked="checked"':'';?>  /></span>
											</td>
											</tr>
											
										<?php
										}
									?>
								</table>
							</div>
							<?php
						break;
					}
	}
	
	function logoimage() {
		$param=$this->PageOptions['general']['content']['logoimage'];
		?>
			<div class='item'>
								<div class='p_ttl'>
								<?php if ($param['hint']!='') { ?>
								<span class='hint' alt='<?php echo $param['hint']?>'><img src='<?php echo get_template_directory_uri()?>/inc/images/hint.png' /></span>
								<?php } ?>
								<span class='span'><?php echo $param['ttl']?></span><div class="separator"></div></div>
								<img src='<?php echo $param['value']?>' alt='' id='up_<?php echo $param['name']?>_img' />
								<input autocomplete='off' class='tinput finput uploadinput' type='text' id="up_<?php echo $param['name']?>" name='<?php echo $param['name']?>' value='<?php echo $param['value']?>' />
								<span class="gc_imageupload wow-button" target="up_<?php echo $param['name']?>">Change</span>
							</div>
		<?php
	}
	
	function customtext() {
		$param=$this->PageOptions['general']['content']['customtext'];
		?>
			<div class='item'>
								<div class='p_ttl'>
								<?php if ($param['hint']!='') { ?>
								<span class='hint' alt='<?php echo $param['hint']?>'><img src='<?php echo get_template_directory_uri()?>/inc/images/hint.png' /></span>
								<?php } ?>
								<span class='span'><?php echo $param['ttl']?></span><div class="separator"></div></div>
								<input autocomplete='off' class='tinput' type='text' name='<?php echo $param['name']?>' value="<?php echo htmlspecialchars($param['value']);?>" />
							</div>
		<?php
	}
	
	function category() {
		
		$params=$this->PageOptions['slider']['content']['category']['value'];
		?>
			<div class='item'>
				<div class='p_ttl'><span class='span'>Number of slides:</span></div>
				<input class='tinput' type='text' name='category[numberposts]' value='<?php echo $params['numberposts']?>' />
			</div>
			<div class='item'>
				<div class='p_ttl'><span class='span'>Category:</span></div>
				<?php
					$categories=get_categories();
				?>
				<select name='category[category]' class='tinput'>
					<option value='0'>All categories</option>
					<?php
						foreach ($categories as $cat) {
							?><option value='<?php echo $cat->cat_ID?>'<?php echo ($cat->cat_ID==$params['category'])?" selected='selected'":""?>><?php echo $cat->name?> (<?php echo $cat->count?>)</option><?php
						}
					?>
				</select>
			</div>
			<div class='item'>
				<div class='p_ttl'><span class='span'>Order by:</span></div>
				<?php
					$categories=get_categories();
				?>
				<select name='category[orderby]' class='tinput'>
					<option value='date'<?php echo ($params['orderby']=='date')?" selected='selected'":""?>>Created</option>
					<option value='modified'<?php echo ($params['orderby']=='modified')?" selected='selected'":""?>>Modified</option>
					<option value='title'<?php echo ($params['orderby']=='title')?" selected='selected'":""?>>Title</option>
				</select>
			</div>
		<?php
	}
	
	function posts() {
		
		$params=$this->PageOptions['slider']['content']['posts']['value'];
		?>
			<div class='item' alt='posts[]'>
				<div class='p_ttl'><span class='span'>Posts:</span></div>
				<?php
					$posts=get_posts('orderby=title&numberposts=0');
					if (is_array($params))foreach ($params as $post_id) {
						$cpost=get_post( $post_id );
						?>
					<select name='posts[]' class='tinput changeselect' alt="<option value='<?php echo $cpost->ID?>'><?php echo $cpost->post_title?></option>">
						<option value='0'>Delete</option>
						<?php
							foreach ($posts as $post) {
								if (!in_array($post->ID,$params)||$post->ID==$post_id) {
								?><option value='<?php echo $post->ID?>'<?php echo (($post->ID==$post_id)?" selected=\"selected\"":"")?>><?php echo $post->post_title?></option><?php
								}
							}
						?>
					</select>
						<?php
					}
					
					if (count($posts)!=count($params)) {
				?>
				
					<select name='' class='tinput addselect'>
						<option value='0'>Select post</option>
						<?php
							foreach ($posts as $post) {
								if (!in_array($post->ID,$params)) {
									?><option value='<?php echo $post->ID?>'><?php echo $post->post_title?></option><?php
								}
							}
						?>
					</select>
				<?php } ?>
			</div>
		<?php
	}
	
	function pages() {
		
		$params=$this->PageOptions['slider']['content']['pages']['value'];
		?>
			<div class='item' alt='pages[]'>
				<div class='p_ttl'><span class='span'>Pages:</span></div>
				<?php
					$pages=get_pages('orderby=title');
					if (is_array($params))foreach ($params as $page_id) {
						$cpage=get_page($page_id);
						?>
					<select name='pages[]' class='tinput changeselect' alt="<option value='<?php echo $cpage->ID?>'><?php echo $cpage->post_title?></option>">
						<option value='0'>Delete</option>
						<?php
							foreach ($pages as $page) {
								if (!in_array($page->ID,$params)||$page->ID==$page_id) {
								?><option value='<?php echo $page->ID?>'<?php echo (($page->ID==$page_id)?" selected=\"selected\"":"")?>><?php echo $page->post_title?></option><?php
								}
							}
						?>
					</select>
						<?php
					}
					
					if (count($pages)!=count($params)||(!is_array($params))) {
				?>
				
					<select name='' class='tinput addselect'>
						<option value='0'>Select page</option>
						<?php
							foreach ($pages as $page) {
								if (!in_array($page->ID,$params)) {
									?><option value='<?php echo $page->ID?>'><?php echo $page->post_title?></option><?php
								}
							}
						?>
					</select>
				<?php } ?>
			</div>
		<?php
	}
	
	function custom_slides() {
		$slides=$this->PageOptions['slider']['content']['custom_slides']['value'];
		?>
		<input type='hidden' name='custom_slides' value='0' />
		<dl class='custom_slides'>
			<?php
				if (count($slides)&&is_array($slides)) {
					$i=0;
					foreach( $slides as $slide) { $i++;?>
						<dt class='slide_ttl'><span class="span_ttl_inner"><?php echo $slide['ttl']?></span><span class="remove">Remove</span></dt>
						<dd class="slide_item">
							<table>
							<tr><td width="20%">Title:</td><td><input type="text" class="slide-name" name="custom_slides[<?php echo $i; ?>][ttl]" value="<?php echo $slide['ttl']?>" /></td></tr>
							<tr><td>Image:</td><td><input class="uploadinput" type="text" name="custom_slides[<?php echo $i; ?>][img]" id="slide-image_<?php echo $i; ?>" value="<?php echo $slide['img']; ?>" /><span target="slide-image_<?php echo $i; ?>" class="gc_imageupload wow-button">Upload</span></td></tr>
							<tr><td>Content:</td><td><input type="text" name="custom_slides[<?php echo $i; ?>][content]" value="<?php echo $slide['content']?>" /></td></tr>
							<tr><td>Link URL:</td><td><input type="text" name="custom_slides[<?php echo $i; ?>][link]" value="<?php echo $slide['link']?>" /></td></tr>
							</table>
						</dd>
					<?php }
				}
			?>
			<dt class="add-new slide_ttl"><span class="span_ttl_inner">Add new slide...</span></dt>
		</dl>

		<?php
	}
	
	function ajax_callback() {
		if ((in_array($_POST['task'],$this->tasks))&&is_callable(array(get_class($this), $this->tasks[0]))) {
            call_user_func(array( get_class($this), $_POST['task']));
        }
		
		die();
	}
	
	function imageupload() {
		
		$exts = array('jpg','png','gif','jpeg','ico');
		$file=$_FILES[$_POST['img']];
		$ext=explode('.',$file['name']);
		$ext=$ext[count($ext)-1];
		if (in_array($ext, $exts)) {
			$override['test_form']=false;
			$file=wp_handle_upload($file,$override);
			
			if (preg_match('/#up_new_slide_img/', $_POST['sender'])) {
				image_resize($file['file'], 56, 56, true, 'prev');
			}
			echo $file['url'];
		} else echo 'Unallowed file extention';
	}
	
	function formsave() {
	
		$option=$_POST['option'];
		if (isset($this->PageOptions[$option])) {
			$options=$_POST;
			unset($options['option']);
			unset($options['task']);
			$options=removeslashes($options);
			update_option($option,$options);
		}
		echo 'New configuration saved';
		
	}
	
	function activate() {
		$data['domain']=$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_NAME'];
		$data['info']=get_theme_data(TEMPLATEPATH.'/style.css');
		$data['info']=$data['info']['Name'];
		$data['theme']=get_template_directory_uri();
		$data['act_key']=(string)$_POST['act_key'];
		$name = $info['Name']?$info['Name']:'WOW Options';
		$url="http://wpwow.com/index.php?activation=4";
		error_reporting(15);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, "Accept: application/xml");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
		$response = curl_exec($ch); 
		curl_close($ch);
		print_r($response);
		if (preg_match('/okbox/', $response)) {
			$save=array('activator'=>'Your theme was successful activated at '.date('Y.m.d').' with activation key '.$data['act_key']);
			update_option('activate',$save); 
		}
	}

}

function removeslashes($var) {
	if (is_array($var)) foreach ($var as $key=>$value) {
		$var[$key]=removeslashes($value);
	} else {
		return stripslashes($var);
	}
	return $var;
}

function wow_feedback_options() {
    
        add_meta_box(
            'feedback_options',
            'Feedback Options',
            'wow_feedback_options_func',
            'page'
        );
    
}

function wow_feedback_options_func ($post) {
	$options = get_post_meta($post->ID, 'feedback-options', true);
	?>
	<script>
			var admin_mail='<?php echo get_option('admin_email'); ?>';
	</script>
	<div class='feedback-options'>
		<h4>Options</h4>
		<p class='description'>To receive message from your visitors, specify your email for feedbacks. If option "Use department email" is enabled, your visitors will be able to choose department which should receive message. Also, if choosen department has not email, message will be sent to the email, specified in this field.</p>
		<label>Target email address</label><input type='text' name='feedback-options[email-for-feedbacks]' value='<?php echo ($options)?$options['email-for-feedbacks']:get_option('admin_email'); ?>' />&nbsp;&nbsp; or &nbsp;&nbsp;<input type='checkbox' name='feedback-options[use-department-emails]' value='1' <?php if (isset($options['use-department-emails'])&&$options['use-department-emails']) echo 'checked="checked"'; ?> /> Use department email
		
	</div>
	<div class='feedback-departments'>
	<h4>Departments</h4>
	<p style='margin-bottom:10px;' class='description'>In this section you ca specify additional contacts of your company. If your company has several departments, you can separate contacts by departments. And in this case your visitors will be able to contact appropriate department. To add new department click by button "Add new...", in the form provided, specify title and email of department. These two fields are required. You can add more contacts for department, to do this click on "Add contact...", specify in the left field of the provided line name of contact (for example Skype, ICQ, Phone, etc.), and contact in the right field (you can leave contact name empty).</p>
	<ul>
		
	</ul>
	<div class='department-details-container'>
		<?php 
			$i=0;
			if (isset($options['department'])&&is_array($options['department'])) foreach( $options['department'] as $department ) { 
			$i++;
		?>
			<div class='department-details' alt='<?php echo $i; ?>'>
				<span class='department-remove button'>Remove this department</span>
				<table>
					<tr>
						<td width='200px'>Title:</td>
						<td>
							<input type='text' name='feedback-options[department][<?php echo $i; ?>][title][value]' class='department-ttl' value='<?php echo $department['title']['value']; ?>' />
							<input type='hidden' name='feedback-options[department][<?php echo $i; ?>][title][name]' value='title' />
						</td>
					</tr>
					<tr>
						<td>Email (show on contact page <input type='checkbox' name='feedback-options[department][<?php echo $i; ?>][email][show]' value='1' <?php echo (isset($department['email']['show'])&&$department['email']['show'])?'checked="checked"':'';?> />):</td>
						<td>
							<input type='text' name='feedback-options[department][<?php echo $i; ?>][email][value]' value='<?php echo $department['email']['value']; ?>' />
							<input type='hidden' name='feedback-options[department][<?php echo $i; ?>][email][name]' value='<?php echo $department['email']['name']; ?>' />
						</td>
					</tr>
					<?php
						unset( $department['title'] );
						unset( $department['email'] );
						$x=0;
						foreach ( $department as $detail ) { $x++; ?>
						<tr>
						<td>
							<input type='text' name='feedback-options[department][<?php echo $i; ?>][<?php echo $x; ?>][name]' value='<?php echo $detail['name']; ?>' />
						</td>
						<td>
							<input type='text' name='feedback-options[department][<?php echo $i; ?>][<?php echo $x; ?>][value]' value='<?php echo $detail['value']; ?>' />
						</td>
						<td width='80px'>
							<span class='detail-remove'>Remove this</span>
						</td>
						</tr>
					<?php } ?>
					<tr>
						<td colspan='3'><div class='button more_details' style='float:right'>Add contact...</div></td>
					</tr>
				</table>
			</div>
		<?php } ?>
		
	</div>
	<script>
		if (jQuery('#page_template').val()=='feedback.php') {
				jQuery('#feedback_options').show();
		}
			jQuery('.department-details').each(function() {
				jQuery('.feedback-departments ul').append(jQuery('<li>').text(jQuery(this).find('.department-ttl').val()));
			});
			jQuery('.feedback-departments ul li:first').click();
			jQuery('.feedback-departments ul').append(
				jQuery('<li>', {
					'class':'newdepartment'
				}).text('Add new...')
			);
			
		</script>
	</div>
	<div class='clear'></div>
	<?php
}
function wow_feedback_options_update ( $post_id ) {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; 
	
    if ( !current_user_can('edit_post', $post_id) ) return false; 
	
	if ( !isset( $_POST['feedback-options'] ) ) return false; 
	
	update_post_meta($post_id, 'feedback-options', $_POST['feedback-options']);
    
	return $post_id;
}
?>