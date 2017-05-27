// Image Upload
jQuery(document).ready(function() {
	jQuery('.p_ttl .hint').mouseenter(function(){
		if (!jQuery(this).hasClass('active')) {
			jQuery(this).addClass('active');
			jQuery(this).html(jQuery(this).html()+"<span>"+jQuery(this).attr('alt')+"</span>");
		}
	}).mouseleave(function() {
		jQuery(this).removeClass('active');
		jQuery('span', this).remove();
	});
	
	jQuery('.tcheck').each(function() {
		if (jQuery('input',this).eq(0).attr('checked')) {
			jQuery(this).css('background-position', '0px 0px');
		} else {
			jQuery(this).css('background-position', '0px 38px');
		}
	});
	
	jQuery('.tcheck').live('click', function() {
		var input=jQuery('input',this).eq(0);
		if (input.attr('checked')) {
			input.attr("checked", false);
			jQuery(this).css('background-position', '0px 38px');
		} else {
			input.attr("checked", true);
			jQuery(this).css('background-position', '0px 0px');
		}
		jQuery('.save_data_btn').css('background-position', 'left top');
	});
	
	jQuery('.reset_data_btn').click(function() {
		var option=jQuery('.tabs-content li.active').attr('id');
		var ttl=jQuery('.tabs-content li.active h2:first').text();
		jQuery('#resetform input').filter(function(){
					return (jQuery(this).attr('name')=='option');
				}).val(option);
				
		jQuery('#resetform').attr('action','/wp-admin/admin.php?page='+option);
		if (confirm('Reset all '+ttl+' options to defaults?'))
		jQuery('#resetform').submit();
	});
	jQuery('.save_data_btn').click(function() {
		jQuery('.ajaxloader').show();
		var senddata = jQuery('.tabs-content li.active form').serialize()+"&task=formsave";
		jQuery('.tabs-content li.active form input:not(:checked)').filter(function() {
			return (jQuery(this).attr('type')=='checkbox');
		}).each(function(){
			senddata=senddata+'&'+jQuery(this).attr('name')+'=0';
		});
			jQuery.ajax({
				url:'admin-ajax.php?action=processing_ajax',
				data: senddata,
				type:"POST",
				success: function(responseText) {
					jQuery('.ajaxloader').hide();
					jQuery('#server_answer').text(responseText);
					jQuery('#server_answer').fadeIn(1000).delay(4000).fadeOut(1000);
				}
			});
			
	});
	jQuery(document).ready(function() {
		jQuery('#form_translations').prepend(
			jQuery('<div>', {'class':'group_ttls'})
		);
		jQuery('.group_ttl').each(function() {
			jQuery(this).appendTo('.group_ttls');
		});
		jQuery('.group_ttl:first').addClass('active');
		jQuery('.group_box:first').addClass('active');
	});
	jQuery('.group_ttl').live('click', function() {
		jQuery(this).addClass('active').siblings().removeClass('active');
		jQuery('.group_box').eq(jQuery(this).index()).addClass('active').siblings().removeClass('active');
	});
	
	
	
	
	
	jQuery('.gc_imageupload').live('click', function() {
		var input=jQuery('#'+jQuery(this).attr('target'));
		var img=jQuery('#'+jQuery(this).attr('target')+'_img');
		var custom_uploader = wp.media({
			title: 'Select Image',
			button: {
				text: 'Select Image'
			},
			multiple: false
		})
		.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			input.val(attachment.url);
			img.attr( 'src', attachment.url );
		})
		.open();
	});
	
	
	jQuery('.slide_ttl').live('click', function() {
		if (jQuery(this).hasClass('add-new')) return;
		jQuery(this).next('dd').toggleClass('active').siblings('dd').removeClass('active');
	});
	
	jQuery('.slide_ttl .remove').live('click', function() {
		jQuery(this).parents('dt').next('dd').remove();
		jQuery(this).parents('dt').remove();
	});
	jQuery('.slide_ttl.add-new').live('click', function() {
		var number=jQuery('.custom_slides dt').length+1;
		jQuery(this).removeClass('add-new').append(
			jQuery('<span>', {
				'class':'remove'
			}).text('Remove')
		).find('.span_ttl_inner').text('New Slide');
		jQuery('.custom_slides').append(
			'<dd class="slide_item">'+
			'<table>'+
				'<tr><td width="20%">Title:</td><td><input type="text" class="slide-name" name="custom_slides['+number+'][ttl]" value="New Slide" /></td></tr>'+
				'<tr><td>Image (1000px x 378px):</td><td><input type="text" name="custom_slides['+number+'][img]" id="slide-image_'+number+'" value="" /><span target="slide-image_'+number+'" class="gc_imageupload wow-button">Upload</span></td></tr>'+
				'<tr><td>Content:</td><td><input type="text" name="custom_slides['+number+'][content]" value="" /></td></tr>'+
				'<tr><td>Link URL:</td><td><input type="text" name="custom_slides['+number+'][link]" value="" /></td></tr>'+
			'</table>'+
			'</dd>'
		).append(
			jQuery('<dt>', {
				'class':'add-new slide_ttl',
			}).append(
				jQuery('<span>', {
					'class':'span_ttl_inner'
				}).text('Add new slide...')
			)
		);
		jQuery(this).next('dd').toggleClass('active').siblings('dd').removeClass('active');
	});
	
	
	
	
	jQuery('.adm-form input').live('change', function() {
		jQuery('.save_data_btn').css('background-position', 'left top');
	});
	jQuery('.adm-form select').live('change', function() {
		jQuery('.save_data_btn').css('background-position', 'left top');
	});
	jQuery('.sidebarselector img').live('click', function() {
		jQuery('.sidebarselector select option').eq(jQuery(this).index()).attr('selected', 'selected');
		jQuery(this).addClass('active').siblings().removeClass('active');
	});
});
jQuery('#page_template').live('change', function() {
				if (jQuery(this).val()=='feedback.php') {
					jQuery('#feedback_options').show()
					location.href='#feedback_options';
				} else {
					jQuery('#feedback_options').hide()
				}
			});
			jQuery('.feedback-departments li').live('click', function() {//department-details-container
				if (jQuery(this).hasClass('newdepartment')) {
					
					var number=jQuery('.department-details').length+1;
					
					
					
					jQuery('.department-details-container').append(
						jQuery('<div>',{
							'class':'department-details',
							'alt':number
						}).append(
							jQuery('<span>', {
								'class':'department-remove button'
							}).text('Remove this department')
						).append(
							jQuery('<table>').append(
								jQuery('<tr>').append(
									jQuery('<td>', {
										'width':'200px'
									}).text('Title')
								).append(
									jQuery('<td>').append(
										jQuery('<input>', {
											'type':'text',
											'name':'feedback-options[department]['+number+'][title][value]',
											'value':'Office name',
											'class':'department-ttl'
										})
									).append(
										jQuery('<input>', {
											'type':'hidden',
											'name':'feedback-options[department]['+number+'][title][name]',
											'value':'title'
										})
									)
								)
							).append(
								jQuery('<tr>').append(
									jQuery('<td>').html('Email (show on contact page <input type="checkbox" name="feedback-options[department]['+number+'][email][show]" value="1" />)')
								).append(
									jQuery('<td>').append(
										jQuery('<input>', {
											'type':'text',
											'name':'feedback-options[department]['+number+'][email][value]',
											'value':admin_mail
										})
									).append(
										jQuery('<input>', {
											'type':'hidden',
											'name':'feedback-options[department]['+number+'][email][name]',
											'value':'Email'
										})
									)
								)
							).append(
								jQuery('<tr>').append(
									jQuery('<td>', {
										'colspan':'3'
									}).append(
										jQuery('<div>', {
											'class':'button more_details'
										}).text('Add contact...')
									)
								)
							)
						)
					);
					jQuery(this).removeClass('newdepartment').text('Office Name');
					jQuery('.feedback-departments ul').append(jQuery('<li>', { 'class':'newdepartment' }).text('Add new...'));
				}
				jQuery(this).addClass('active').siblings().removeClass('active');
				jQuery('.department-details').eq(jQuery(this).index()).show().siblings().hide();
			});
			jQuery('.more_details').live('click', function() {
				var number=jQuery(this).parents('.department-details').attr('alt');
				var option=jQuery(this).parents('table').find('tr').length;
				jQuery(this).parents('tr').before(
					jQuery('<tr>').append(
						jQuery('<td>').append(
							jQuery('<input>', {
								'type':'text',
								'name':'feedback-options[department]['+number+']['+option+'][name]',
								'value':''
							})
						)
					).append(
						jQuery('<td>').append(
							jQuery('<input>', {
								'type':'text',
								'name':'feedback-options[department]['+number+']['+option+'][value]',
								'value':''
							})
						)
					).append(
						jQuery('<td>', {
							'width':'80px'
						}).append(
							jQuery('<span>', {
								'class':'detail-remove button'
							}).text('Remove this')
						)
					)
				);
			});
			jQuery('.detail-remove').live('click', function() {
				jQuery(this).parents('tr').remove();
			});
			jQuery('.department-remove').live('click', function() {
				jQuery('.feedback-departments ul li').eq(jQuery(this).parents('.department-details').index()).remove();
				jQuery(this).parents('.department-details').remove();
				if (!jQuery('.feedback-departments ul li:first').hasClass('newdepartment')) jQuery('.feedback-departments ul li:first').click();
			});
			jQuery('.department-details .department-ttl').live('change', function() {
				var index=jQuery(this).parents('.department-details').index();
				jQuery('.feedback-departments li').eq(index).text(jQuery(this).val());
			});
	jQuery('.activate').live('click', function() {
		var btn=jQuery(this);
		btn.after(jQuery("#imgloader")).hide().next("#imgloader").css('margin-top', '20px').show();
		var params = new Array();
		jQuery('#activation-params input').each( function(i) {
			params[i]=jQuery(this).attr('name')+'='+jQuery(this).val();
		});
		params=params.join('&');
		jQuery('#sActivator').attr('src', 'http://wpwow.com/activation/?'+params);
	});