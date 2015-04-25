
jQuery(document).ready(function ($) {
    var current_url = window.location;
    var loc = window.location.href,
            index = loc.indexOf('#');

    if (index > 0) {
        current_url = loc.substring(0, index);
    }
    var magic_url = current_url + '&wplikebox_magic_data=1';
    console.log(magic_url);
    $('#magicsuggest').magicSuggest({
        data: magic_url ,
        ajaxConfig: {
            xhrFields: {
                withCredentials: true,
            }
        }
    });
    $( document ).tooltip({
        content: function() {
        var element = $( this );
            if ( element.is( "[help]" ) ) {
                return 'The Minimum width is 280px & Max is 500px';
            }
            if ( element.is( "[help1]" ) ) {
                return 'The Minimum height of the Like Box is 130px';
            }
        }
        
    });
});


jQuery(document).ready(function ($) {
    
    $('#magicsuggest').magicSuggest({
        // [...] // configuration options
    });
    wpfblikebox_func();
});
jQuery(function () {
    
});
function wpfblbox_switchonoff(val) {
    var path = jQuery(val).attr("src");
    var file = path.split('/').pop();
    var file2 = path.split(file);
    console.log(file2[0]);
    var on = '';
    var off = '';
    if (file == 'on.png') {
        on = true;
    } else {
        off = true;
    }
    if (off)
    {
        jQuery.post('', {'wpfblbox_switchonoff': 1}, function (e) {
            if (e == 'error') {
                error('error');
            } else {
                jQuery('#wpfblbox_circ').css("background", "#0f0");
                jQuery(val).attr("src", file2[0] + 'on.png');
            }
        });
    }
    if (on) {
        jQuery.post('', {'wpfblbox_switchonoff': 0}, function (e) {
            if (e == 'error') {
                error('error');
            } else {
                jQuery('#wpfblbox_circ').css("background", "#f00");
                jQuery(val).attr("src", file2[0] + 'off.png');
            }
        });
    }
    //alert(val);
}


(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=575748072558072";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
function isNumeric(value) {
    var bool = isNaN(+value);
    bool = bool || (value.indexOf('.') != -1);
    bool = bool || (value.indexOf(",") != -1);
    return !bool;
};
function wpfblikebox_func() {

    var width = jQuery('#width').val();
    console.log('width = '+width);
    var height = jQuery('#height').val()
    if (width < 280 || width > 500) {
        //alert("Width should be more than 280 and less than 500");
        jQuery('#width_error').show();
        width = 340;
        jQuery('#width').val(340)
    }
    if (height < 130) {
        //alert("The maximum pixel height of the plugin. Min. is 130");
        jQuery('#height_error').show();
        height = 500;
        jQuery('#height').val(500)
    }
//}

    var color = jQuery('#color').val();
    var faces = jQuery('#faces').is(':checked');
    var headers = jQuery('#headers').is(':checked');
    var border = jQuery('#border').is(':checked');
    var showposts = jQuery('#showposts').is(':checked');
    var cover = jQuery('#cover_photo').is(':checked');
    if(faces){faces = true;}else{faces=false;}
    if(headers){headers = true;}else{headers=false;}
    if(border){border = true;}else{border=false;}
    if(showposts){showposts = true;}else{showposts=false;}
    if(cover){cover = true;}else{cover=false;}
    var url = jQuery('#url_text').val();
    console.log(url);
    if(url == '' || url == null){
        url = 'https://www.facebook.com/FacebookDevelopers';
    }
    
    var token = url.indexOf('http://');
    if(token == -1){token = url.indexOf('https://');}
    if(token == -1){url = 'http://'+url;}
    var data = '<div class="fb-page" data-href="'+url+'" data-height="'+height+'" data-width="'+width+'" data-hide-cover="'+cover+'" data-show-facepile="'+faces+'" data-show-posts="'+showposts+'"  data-show-border="'+border+'"><div class="fb-xfbml-parse-ignore"><blockquote cite="'+url+'"><a href="'+url+'">'+url+'</a></blockquote></div></div>';
 
    jQuery('#u_0_18').html(data);console.log(token);
    
    FB.XFBML.parse();
    return false;
}

//------------------------------------------------------------------------------


 