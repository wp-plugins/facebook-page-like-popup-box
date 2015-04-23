jQuery(document).ready(function(){
/** lb_l_ret */

/* pre-construct code! */
jQuery('body').append(construct_code());

	if (lb_l_ret.show_once>0 && arvreadCookie("arevico_lb")==1)
	{

	} else {

		window.setTimeout(show_facebox, lb_l_ret.delay)
	}

});

function show_facebox(){
	if (lb_l_ret.show_once>0){
		arvcreateCookie("arevico_lb", "1", lb_l_ret.show_once);
	}
$jarevico('a#inline').arevicofancy({
	'modal': false,
	'padding' : 0,
	'autoDimensions':false,
	'width' : '280',
	'height': 'auto',
	'scrolling'          : 'no',
	'centerOnScroll' : true,
	'hideOnOverlayClick' : (lb_l_ret.coc == 1)
	}).trigger('click');
}

function arvcreateCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function arvreadCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

/**
 *
 * @access public
 * @return void
 **/
 (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
function construct_code(){
fbl_otp='<a id="inline" href="#arvlbdata" style="display: none;">Show</a><div style="display:none"><div id="arvlbdata" style="overflow:hidden;' + '' + '">';
fbl_otp += '<div id="fb-root"></div>';
fbl_otp += '<div class="fb-page" data-href="https://www.facebook.com/'+ lb_l_ret.fb_id +'" data-hide-cover="false" data-show-facepile="true" data-show-posts="false" style="height:225px;"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/'+ lb_l_ret.fb_id +'"><a href="https://www.facebook.com/'+ lb_l_ret.fb_id +'">'+ lb_l_ret.fb_id +'</a></blockquote></div></div>';
fbl_otp +='</div></div>';
return fbl_otp;
}
