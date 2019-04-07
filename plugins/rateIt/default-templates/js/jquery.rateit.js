/* -- BEGIN LICENSE BLOCK ----------------------------------
 * This file is part of rateIt, a plugin for Dotclear 2.
 *
 * Copyright (c) 2009-2010 JC Denis and contributors
 * jcdenis@gdwd.com
 *
 * Licensed under the GPL version 2.0 license.
 * A copy of this license is available in LICENSE file or at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -- END LICENSE BLOCK ------------------------------------*/

(function($) {
	$.fn.rateit = function(options) {
		var opts = $.extend({}, $.fn.rateit.defaults, options);

		return this.each(function() {
			parseRateit(this,opts.service_url,opts.service_func,opts.blog_uid,opts.enable_cookie,opts.image_size,opts.msg_thanks);
		});
	};

	function parseRateit(target,service_url,service_func,blog_uid,enable_cookie,image_size,msg_thanks) {

		$.fn.rating.options.required = true;
		$.fn.rating.options.starWidth = image_size;

		var bloc = target;

		$(target).find('.rateit-linker').each(function(){

			var type = $(this).find('input[name=linkertype]').val();
			var id = $(this).find('input[name=linkerid]').val();
			var uid = $(this).find('input[name=linkeruid]').val();
			var dis = $(this).find('input[disabled=disabled]').val();

			if (type==undefined || id==undefined || uid==undefined) return;

			if (dis!=undefined)
				$('input.rateit-'+type+'-'+id).rating({readOnly:true});
			else {
				$(function(){
					$('input.rateit-'+type+'-'+id).rating({
						callback:function(note,link){
							$('input.rateit-'+type+'-'+id).rating({readOnly:true});
							if ($('input.rateit-'+type+'-'+id).hasClass('rateit-loop-prevent')){
								/*$('input.rateit-'+type+'-'+id).rating('disable');*/
							}
							else{
								$.ajax({
									timeout:3000,
									url:service_url,
									type:'POST',
									data:{f:service_func,voteType:type,voteId:id,voteNote:note},
									error:function(){alert('Failed to call server');},
									success:function(data){
										data=$(data);
										if(data.find('rsp').attr('status')=='ok'){

											$('input.rateit-'+type+'-'+id).addClass('rateit-loop-prevent');

											var n=Math.round(parseFloat(data.find('item').attr('note')))-1;
											$('input.rateit-'+type+'-'+id).rating('select',n);
											$('input.rateit-'+type+'-'+id).rating('disable');

											$('*').find('.rateit-total-'+type+'-'+id).each(function(){$(this).text(data.find('item').attr('total'))});
											$('*').find('.rateit-max-'+type+'-'+id).each(function(){$(this).text(data.find('item').attr('max'))});
											$('*').find('.rateit-min-'+type+'-'+id).each(function(){$(this).text(data.find('item').attr('min'))});
											$('*').find('.rateit-maxcount-'+type+'-'+id).each(function(){$(this).text(data.find('item').attr('maxcount'))});
											$('*').find('.rateit-mincount-'+type+'-'+id).each(function(){$(this).text(data.find('item').attr('mincount'))});
											$('*').find('.rateit-note-'+type+'-'+id).each(function(){$(this).text(data.find('item').attr('note'))});
											$('*').find('.rateit-quotient-'+type+'-'+id).each(function(){$(this).text(data.find('item').attr('quotient'))});
											$('*').find('.rateit-fullnote-'+type+'-'+id).each(function(){$(this).text(data.find('item').attr('note')+'/'+data.find('item').attr('quotient'))});

											if (msg_thanks!=''){
												$('*').find('.rateit-linker-'+type+'-'+id).each(function(){$(this).empty().append('<p>'+msg_thanks+'</p>')});
											}
										}else{
											alert($(data).find('message').text());
										}
									}
								});
							}
						}
					});
				});
			}
			$(this).children('p').children('input:submit').hide();
			$(this).children('p').after('<p>&nbsp;</p>');
		});
		return target;
	}

	$.fn.rateit.defaults = {
		service_url: '',
		service_func: 'rateItVote',
		blog_uid: '',
		enable_cookie: 0,
		image_size: 16,
		msg_thanks: 'Thank you for having voted'
	};

})(jQuery);
