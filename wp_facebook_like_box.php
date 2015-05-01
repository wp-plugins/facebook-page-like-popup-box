<?php
/*
  Plugin Name: Facebook Page Like Popup Box
  Description: Facebook Page Like Popup Box allows you to add Facebook like box to your wordpress blog.
  Author: Tayyab
   Author URI:https://www.google.com/+tayyabismail0o1 
  Version: 1.2
  Copyright: 2015, Tayyab
 */
require_once( ABSPATH . "wp-includes/pluggable.php" );
add_action('admin_menu', 'wpfblbox_plugin_setup_menu');
//register_uninstall_hook( __FILE__, 'uninstall_hook');
register_deactivation_hook(__FILE__, 'wpfblbox_uninstall_hook');

add_shortcode('wpfblikebox', 'wp_fb_like_box');
//--------------------------------------------------------/*
/*class wp_my_plugin extends WP_Widget {
    
    // Controller
function __construct() {
	$widget_ops = array('classname' => 'my_widget_class', 'description' => __('Insert the plugin description here', 'wp_widget_plugin'));
	$control_ops = array('width' => 400, 'height' => 300);
	parent::WP_Widget(false, $name = __('Facebook Page Like Popup Box', 'wp_widget_plugin'), $widget_ops, $control_ops );
}

    // constructor
    function wp_my_plugin() {
        parent::WP_Widget(false, $name = __('Facebook Page Like Popup Box', 'wp_widget_plugin') );

    }

    // widget form creation
    // widget form creation
    function form($instance) {

// Check values
        if ($instance) {
            $title = esc_attr($instance['title']);
            $text = esc_attr($instance['text']);
            $textarea = esc_textarea($instance['textarea']);
        } else {
            $title = '';
            $text = '';
            $textarea = '';
        }
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Facebook Page Like Popup Box', 'wp_widget_plugin'); ?></label>
        </p>
        <?php
    }

    // widget update
    // update widget
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        // Fields
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['text'] = strip_tags($new_instance['text']);
        $instance['textarea'] = strip_tags($new_instance['textarea']);
        return $instance;
    }

    // widget display
    // display widget
    function widget($args, $instance) {
        extract($args);
        // these are the widget options
        /*$title = apply_filters('widget_title', $instance['title']);
        $text = $instance['text'];
        $textarea = $instance['textarea'];
        echo $before_widget;
        // Display the widget
        echo '<div class="widget-text wp_widget_plugin_box">';

        // Check if title is set
        if ($title) {
            echo $before_title . $title . $after_title;
        }

        // Check if text is set
        if ($text) {
            echo '<p class="wp_widget_plugin_text">' . $text . '</p>';
        }
        // Check if textarea is set
        if ($textarea) {
            echo '<p class="wp_widget_plugin_textarea">' . $textarea . '</p>';
        }
        echo '</div>';*/
        /*echo wp_fb_like_box();
    }

}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_my_plugin");'));*/
//--------------------------------------------------------

// Add settings link on plugin page
function fbpopup_fb_like_box_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=wp_facebook-like-box&edit=1">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'fbpopup_fb_like_box_settings_link' );


function fbpopup_wpfblikebox_abwb1()
{
        wp_register_style('css2', plugins_url('/css/fb-popup.css', __FILE__));
        wp_enqueue_style('css2');
        wp_enqueue_script('jquery-ui-core', array('jquery'));
        wp_enqueue_script('pluginscript1', plugins_url('/js/jq-fb-popup.js', __FILE__), array('jquery'));  
}
add_action('wp_enqueue_scripts', 'fbpopup_wpfblikebox_abwb1');

function wpfblbox_uninstall_hook() {
    global $wpdb;
    $thetable = $wpdb->prefix . "wpfblbox";
    //Delete any options that's stored also?
    $wpdb->query("DROP TABLE IF EXISTS $thetable");
}

function wpfblbox_plugin_setup_menu() {
    global $wpdb;
    $table = $wpdb->prefix . 'wpfblbox';
    $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    if ($myrows[0]->status == 0) {
        add_menu_page('Facebook Popup Box Settings', 'FB Popup Box <span id="wpfblbox_circ" class="update-plugins count-1" style="background:#F00"><span class="plugin-count">&nbsp&nbsp</span></span>', 'manage_options', 'wp_facebook-like-box', 'wpfblbox_init', plugins_url("/images/ico.png", __FILE__));
    } else {
        add_menu_page('Facebook Popup Box Settings', 'FB Popup Box <span id="wpfblbox_circ" class="update-plugins count-1" style="background:#0F0"><span class="plugin-count">&nbsp&nbsp</span></span>', 'manage_options', 'wp_facebook-like-box', 'wpfblbox_init', plugins_url("/images/ico.png", __FILE__));
    }
}

add_filter('wp_head', 'wpfblbox_header');
add_filter('the_content', 'wp_fb_like_box');

function wpfblbox_header() {
    $post_id = get_the_ID();
    global $wpdb;
    $table = $wpdb->prefix . 'wpfblbox';
    $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    $status = $myrows[0]->status;
    if ($status != 0) {
        ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=575748072558072";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
        <?php
    }
}

add_action('init', function() {
    // yes, this is a PHP 5.3 closure, deal with it
    if (!isset($_COOKIE['crud_fblbox_my_cookie'])) {
        setcookie('crud_fblbox_my_cookie', 'some default value', strtotime('+1 day'));
    }
});

function wp_fb_like_box($content = NULL) {
    $post_id = get_the_ID();
    global $wpdb;
    $table = $wpdb->prefix . 'wpfblbox';
    $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    $status = $myrows[0]->status;
    $color = $myrows[0]->color;
    $display = $myrows[0]->display;
    $except_ids = $myrows[0]->except_ids;
    $fbpage = $myrows[0]->fbpage;
    $width = $myrows[0]->width;
    $height = $myrows[0]->height;
    $str = $content;
    $faces = $myrows[0]->faces;
    $border = $myrows[0]->border;
    $header = $myrows[0]->header;
    $posts = $myrows[0]->posts;
    $users = $myrows[0]->users;
    $when_display = $myrows[0]->when_display;
    $cover_photo = $myrows[0]->cover_photo;
    $mobile = $myrows[0]->mobile;
    if($cover_photo == 1){
        $cover_photo = "true";
    }else{
        $cover_photo = "false";
    }
    if ($faces == 1) {
        $faces = 'true';
    } else {
        $faces = 'false';
    }
    if ($border == 1) {
        $border = 'true';
    } else {
        $border = 'false';
    }
    if ($header == 1) {
        $header = 'true';
    } else {
        $header = 'false';
    }
    if ($posts == 1) {
        $posts = 'true';
    } else {
        $posts = 'false';
    }
    $actual_link = $fbpage;
    //checkCookie()
    $fb = '';
    if (($mobile == 1 && wp_is_mobile()) || $mobile == 0) {
        if ($when_display == 0) {

            if (!isset($_COOKIE['crud_fblbox_my_cookie'])) {
                if ($content != NULL) {
                    $fb .= '<script>jQuery(document).ready(function () {if(true){jQuery.jazzPopup.open({ items: { src: "#test-popup", crox: "'.plugins_url("/images/crox.png", __FILE__).'" }, type: "", removalDelay: 500,closeOnBgClick: false, closeMarkup: "<img class=\'mfp-close\' src=\'' . plugins_url("/images/close.png", __FILE__) . '\'>", callbacks: {beforeOpen: function() {this.st.image.markup = this.st.image.markup.replace("mfp-figure", "mfp-figure mfp-with-anim");this.st.mainClass = "fade" ;}}  });}})</script>';
                    $fb .= '<style>.wpfblikebox .fb_iframe_widget span{width:' . $width . 'px !important;}</style>';
                    $fb .= '<div id="test-popup" class="white-popup mfp-with-anim mfp-hide" style="max-width:' . $width . 'px; width:' . $width . 'px;">';
                }
                //$fb .= '<div class="fb-like-box" data-href="' . $actual_link . '" data-height="' . $height . '" data-width="' . $width . '" data-colorscheme="' . $color . '" data-show-faces="' . $faces . '" data-header="' . $header . '" data-stream="' . $posts . '" data-show-border="' . $border . '"></div>';
                $fb .= '<div class="fb-page" data-href="' . $actual_link . '" data-height="' . $height . '" data-width="' . $width . '" data-hide-cover="'.$cover_photo.'" data-show-facepile="' . $faces . '" data-show-posts="' . $posts . '"><div class="fb-xfbml-parse-ignore"><blockquote cite="' . $actual_link . '"></blockquote></div></div>';
                if ($content != NULL) {
                    $fb .= '</div>';
                }
            }
        } else {
            if ($content != NULL) {
                $fb .= '<script>jQuery(document).ready(function () {if(true){jQuery.jazzPopup.open({ items: { src: "#test-popup" , crox: "'.plugins_url("/images/crox.png", __FILE__).'" }, type: "", removalDelay: 500,closeOnBgClick: false, closeMarkup: "<img class=\'mfp-close\' src=\'' . plugins_url("/images/close.png", __FILE__) . '\'>", callbacks: {beforeOpen: function() {this.st.image.markup = this.st.image.markup.replace("mfp-figure", "mfp-figure mfp-with-anim");this.st.mainClass = "fade" ;}}  });}})</script>';
                $fb .= '<style>.wpfblikebox .fb_iframe_widget span{width:' . $width . 'px !important;}</style>';
                $fb .= '<div id="test-popup" class="white-popup mfp-with-anim mfp-hide wpfblikebox" style="max-width:' . $width . 'px; width:' . $width . 'px;">';
            }
            //$fb .= '<div class="fb-like-box" data-href="' . $actual_link . '" data-height="' . $height . '" data-width="' . $width . '" data-colorscheme="' . $color . '" data-show-faces="' . $faces . '" data-header="' . $header . '" data-stream="' . $posts . '" data-show-border="' . $border . '" hide_cover="'.$cover_photo.'"></div>';
            
                $fb .= '<div class="fb-page" data-href="' . $actual_link . '" data-height="' . $height . '" data-width="' . $width . '" data-hide-cover="'.$cover_photo.'" data-show-facepile="' . $faces . '" data-show-posts="' . $posts . '"><div class="fb-xfbml-parse-ignore"><blockquote cite="' . $actual_link . '"></blockquote></div></div>';
            if ($content != NULL) {
                $fb .= '<img class="jazzclosebutton" src="'.plugins_url("/images/crox.png", __FILE__).'" onclick="jQuery.jazzPopup.close();"></div>';
            }
        }
    }

    $width = $myrows[0]->width . 'px';
    if ($status == 0) {
        $str = $content;
    } else {
        if ($content == NULL) {
            $str = $fb;
        }
        if (($users == 0 && is_user_logged_in()) || ($users == 1 && !is_user_logged_in()) || $users == 2) {
            
            if ($display & 2) {
                if (is_page() && !defined('is_front_page')) {
                    $str = $content . $fb;
                }
            }
            if ($display & 1) {
                if (is_front_page()) {
                    $str = $content . $fb;
                }
            }
            if ($display & 4) {
                if (is_single()) {
                    $str = $content . $fb;
                }
            }
            if ($display & 8) {
                //$str = $content . $fb;
            }
        }
    }
    $except_check = true;
    if ($display & 8) {
        @$expect_ids_arrays = split(',', $except_ids);
        foreach ($expect_ids_arrays as $id) {
            if (trim($id) == $post_id) {
                $except_check = false;
            }
        }
    }
    if ($except_check) {
        return $str;
    } else {
        return $content;
    }
}

function wpfblikebox_($needle, $haystack) {
    return strpos($needle, $haystack) !== false;
}

if (isset($_REQUEST['update_wpfblikebox'])) {
//    print_r($_REQUEST);
//    die; 
    //die;
    global $wpdb;
    $type = '';
    $display = $_REQUEST['display'];
    $display_val = 0;
    foreach ($display as $d) {
        $display_val += @mysql_real_escape_string($d);
    }
    $except_ids = (isset($_REQUEST['except_ids'])) ? $_REQUEST['except_ids'] : '';
    if ($except_ids != NULL) {
        $except_ids = implode(', ', $except_ids);
    }
    $color = @mysql_real_escape_string($_REQUEST['color']);
    $height = @mysql_real_escape_string($_REQUEST['height']);
    $width = @mysql_real_escape_string($_REQUEST['width']);
    $mobile = @mysql_real_escape_string($_REQUEST['mobile']);
    $delay = @mysql_real_escape_string($_REQUEST['delay']);
    $when_display = @mysql_real_escape_string($_REQUEST['when_display']);
    $fbpage = @mysql_real_escape_string($_REQUEST['fbpage']);
    $mobile = @mysql_real_escape_string($_REQUEST['mobile']);
    $edit_id = @mysql_real_escape_string($_REQUEST['edit']);
    $faces = @mysql_real_escape_string($_REQUEST['faces']);
    $header = @mysql_real_escape_string($_REQUEST['header']);
    $border = @mysql_real_escape_string($_REQUEST['border']);
    $posts = @mysql_real_escape_string($_REQUEST['posts']);
    $users = @mysql_real_escape_string($_REQUEST['users']);
    $cover_photo = @mysql_real_escape_string($_REQUEST['cover_photo']);

    ($edit_id == 0 || $edit_id == '') ? $edit_id = 1 : '';
    $ul = '0';
    global $current_user;
    get_currentuserinfo();
    if ($each_page_url != NULL) {
        if (!wpfblikebox_($each_page_url, 'http://')) {
            if (!wpfblikebox_($each_page_url, 'https://')) {
                $each_page_url = 'https://' . $each_page_url;
            }
        }
    }
    if (isset($current_user)) {
        $ul = $current_user->ID;
    }
    if ($status == 'on') {
        $status = 1;
    }
    if ($status == 'off') {
        $status = 0;
    }
    $user_id = $ul;


    $data = ($_REQUEST['content']);
    $table = $wpdb->prefix . 'wpfblbox';
    $data1 = array(
        'display' => $display_val,
        'width' => $width,
        'height' => $height,
        'mobile' => $mobile,
        'delay' => $delay,
        'when_display' => $when_display,
        'fbpage' => $fbpage,
        'mobile' => $mobile,
        'header' => $header,
        'border' => $border,
        'posts' => $posts,
        'users' => $users,
        'except_ids' => $except_ids,
        'color' => $color,
        'cover_photo' => $cover_photo,
        'user_id' => $user_id,
        'active' => 1,
        'faces' => $faces,
        'last_modified' => current_time('mysql')
    );
    $v = $wpdb->update($table, $data1, array('id' => $edit_id));
    header('Location:' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
}

if (isset($_REQUEST['wpfblbox_switchonoff'])) {
    global $wpdb;
    $val = $_REQUEST['wpfblbox_switchonoff'];
    $data = array(
        'status' => $val
    );
    $table = $wpdb->prefix . 'wpfblbox';
    if ($wpdb->update($table, $data, array('id' => 1))) {
        echo $val;
    } else {
        echo 'error';
    };
    die;
}
if (isset($_REQUEST['wplikebox_magic_data'])) {
    $data = '';
    $args = array(
        'post_type' => 'any',
        'post_status' => 'publish',
        'posts_per_page' => -1
    );
    $posts = get_posts($args);
    foreach ($posts as $post) {
        $data[] = array('id' => $post->ID, 'name' => $post->post_title);
    }

    echo json_encode($data);
    exit();
}

function wpfblbox_init() {
    if (!isset($_REQUEST['edit'])) {
        echo '<script>location = location+"&edit=1"</script>';
    }
    global $wpdb;
    add_filter('admin_head', 'wpfblbox_ShowTinyMCE');
    $check = array();
    $setting = array('media_buttons' => false);
    $table = $wpdb->prefix . 'wpfblbox';
    if (!isset($_REQUEST['edit'])) {
        header('Location:' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '&edit=1');
    }
    if (!(isset($_REQUEST['new']) || isset($_REQUEST['edit']))) {
        $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    } else if (isset($_REQUEST['edit'])) {
        $edit_id = $_REQUEST['edit'];
        $str = "SELECT * FROM $table WHERE id = 1";
        $myrows = $wpdb->get_results($str);
    }
    $data = '';
    $data_array = array();
    if ($myrows[0]->display & 1) {
        $display[1] = 'checked';
    };
    if ($myrows[0]->display & 2) {
        $display[2] = 'checked';
    };
    if ($myrows[0]->display & 4) {
        $display[4] = 'checked';
    };
    if ($myrows[0]->display & 8) {
        $display[8] = 'checked';
    };
    $when_display[$myrows[0]->when_display] = 'checked';
    $users[$myrows[0]->users] = 'checked';
    $delay = $myrows[0]->delay;
    $mobile[$myrows[0]->mobile] = 'checked';
    $color[$myrows[0]->color] = ' selected="selected"';
    ?>
    <div id="test-popup" class="wpfblbox_white-popup wpfblbox_mfp-with-anim wpfblbox_ mfp-hide"></div>
    <div class="wpfblbox_container wpfblbox">
        <div class="wpfblbox_row">
            <div class="wpfblbox_plugin-wrap wpfblbox_col-md-12">
                <div class="wpfblbox_plugin-notify">
                    <div class="wpfblbox_forms-wrap">
                        <div class="wpfblbox_colmain">
                            <div class="wpfblbox_what">
                                <div class="wpfblbox_form-types-wrap">
                                    <input type="hidden" name="wpfblbox" value="<?php echo $notify; ?>">
                                    <div class="wpfblbox_clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="wpfblbox_col" style="width:67%; ">
                            <div class="wpfblbox_where">
                                <form class="wpfblbox_inline-form wpfblbox_form-inline" method="post">
                                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                                    <input type="hidden" name="site_url" value="<?php echo get_site_url(); ?>" id="site_url">
                                    <div class="wpfblbox_control-group">
                                        <label class="wpfblbox_control-label">Settings</label>
                                        <table border="0" width="100%">
                                            <tr>
                                                <td style="width: 160px; text-align: right; padding-right: 15px;">
                                                    <label style="margin-top:8px;">What to like </label>
                                                </td>
                                                <td>
                                                    <div class="wpfblbox_form-group">
                                                        <input type="text" placeholder="https://www.facebook.com/FacebookDevelopers" onblur="wpfblikebox_func()" name="fbpage" id="url_text"  value="<?php echo @$myrows[0]->fbpage; ?>" class="wpfblbox_form-control">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <hr>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 160px; vertical-align: top;text-align: right; padding-right: 15px;">
                                                    <label style="margin-top:8px;">Where to display? </label>
                                                </td>
                                                <td>
                                                    <div class="wpfblbox_form-group">
                                                        <input type="checkbox" id="display1" name="display[]" <?php echo @$display['1']; ?> value="1" class="wpfblbox_form-control wpfblbox_check" style="float:left"><label for="display1">Homepage</label>
                                                        <input type="checkbox" id="display2" name="display[]" <?php echo @$display['2']; ?> value="2" class="wpfblbox_form-control wpfblbox_check" style="float:left"><label for="display2">All pages</label>
                                                        <input type="checkbox" id="display4" name="display[]" <?php echo @$display['4']; ?> value="4" class="wpfblbox_form-control wpfblbox_check" style="float:left"><label for="display4">All posts</label>
                                                        <input type="checkbox" id="display8" onchange="if (this.checked) {
                                                                        jQuery('.wpfblbox_exclude').show(200)
                                                                    } else {
                                                                        jQuery('.wpfblbox_exclude').hide(200)
                                                                    }" name="display[]" <?php echo @$display['8']; ?> value="8" class="wpfblbox_form-control wpfblbox_check" style="float:left"><label for="display8">Exclude following pages and posts</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;width: 160px; padding-top: 10px;text-align: right; padding-right: 15px;">
                                                    <label class="wpfblbox_exclude" style="display:<?php
                                                    if ($myrows[0]->display & 8) {
                                                        echo 'block';
                                                    } else {
                                                        echo 'none';
                                                    }
                                                    ?>">Exclude Page/Post</label>
                                                </td>
                                                <td>
                                                    <div class="wpfblbox_form-group wpfblbox_exclude" style="display:<?php
                                                    if ($myrows[0]->display & 8) {
                                                        echo 'block';
                                                    } else {
                                                        echo 'none';
                                                    }
                                                    ?>">
                                                        <div id="magicsuggest" value="[<?php echo $myrows[0]->except_ids; ?>]" name="except_ids[]" style="width:auto !important; background: #fff; border: thin solid #cccccc;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <hr>
                                                </td>
                                            </tr>
                                            <tr class="wpfblbox_manual">
                                                <td style="width: 160px; text-align: right; padding-right: 15px;">
                                                    <label>Shortcode </label>
                                                </td>
                                                <td>
                                                    <div class="wpfblbox_form-group">   
                                                        Use shortcode <input style="width:104px;" type="text" value="[wpfblikebox]" onClick="this.setSelectionRange(0, this.value.length);"> to display like button
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="wpfblbox_manual">
                                                <td style="width: 160px; text-align: right; padding-right: 15px; vertical-align: top;">
                                                    <label style="margin-top:10px;">Code Snippet </label>
                                                </td>
                                                <td>
                                                    <div class="wpfblbox_form-group">
                                                        <span>
                                                            Also, you can use following code and paste it in theme files.<br>
                                                            For instance, add following code to header or footer.php to display like button
                                                        </span>
                                                        <input type="text"  onClick="this.setSelectionRange(0, this.value.length);" name="code_snippet" value="<?php echo("<?php echo wp_fb_like_box(); ?>"); ?>" class="wpfblbox_form-control">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <hr>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 160px; padding-top: 11px; vertical-align: top;text-align: right; padding-right: 15px;">
                                                    <label>Display for the first time only?</label>
                                                </td>
                                                <td>
                                                    <div class="wpfblbox_form-group">
                                                        <table class="wpfblbox_eachpage">
                                                            <tr>
                                                                <td>
                                                                    <input type="radio" id="when_display_first" name="when_display" <?php echo @$when_display[0]; ?> value="0" class="wpfblbox_form-control" style="float:left"><label style="float:left; font-weight: normal;" for="when_display_first">Yes, only for the first time</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="radio" id="when_display_all" name="when_display" <?php echo @$when_display[1]; ?> value="1" class="wpfblbox_form-control" style="float:left"><label style="float:left; font-weight: normal;" for="when_display_all">No, all time</label>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>                                                                        
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px; padding-top: 11px; vertical-align: top;text-align: right; padding-right: 15px;">
                                                    <label>Display to users</label>
                                                </td>
                                                <td>
                                                    <div class="wpfblbox_form-group">
                                                        <table class="wpfblbox_eachpage">
                                                            <tr>
                                                                <td>
                                                                    <input type="radio" id="userloged" name="users" <?php echo @$users['0']; ?> value="0" class="wpfblbox_form-control" style="float:left"><label style="float:left; font-weight: normal;" for="userloged">Logged in users only</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="radio" id="usernotloged" name="users" <?php echo @$users['1']; ?> value="1" class="wpfblbox_form-control" style="float:left"><label style="float:left; font-weight: normal;" for="usernotloged">None Logged in users only</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="radio" id="userboth" name="users" <?php echo @$users['2']; ?> value="2" class="wpfblbox_form-control" style="float:left"><label style="float:left; font-weight: normal;" for="userboth">Both</label>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>                                                                        
                                                </td>
                                            </tr>
    <!--                                            <tr>
                                                <td style="width: 200px; padding-top: 11px; text-align: right; padding-right: 15px;">
                                                    <label>Like Box delay after page load</label>
                                                </td>
                                                <td>
                                                    <div class="wpfblbox_form-group">
                                                        <table class="wpfblbox_eachpage">
                                                            <tr>
                                                                <td>
                                                                    <input onblur="if (!isNumeric(this.value)) {
                                                                                    alert('Only digits allowed');
                                                                                    this.focus();
                                                                                }" type="text" id="delay " placeholder="" style="width:80%; float: left;" name="delay" value="<?php echo $delay; ?>" class="wpfblbox_form-control">
                                                                    <img src="<?php echo plugins_url("/images/help.png", __FILE__) ?>" style="float:right" help="" title="help">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>                                                                        
                                                </td>
                                            </tr>-->
<!--                                            <tr>
                                                <td style="width: 160px; padding-top: 11px; vertical-align: top;text-align: right; padding-right: 15px;">
                                                    <label>Display on mobile</label>
                                                </td>
                                                <td>
                                                    <div class="wpfblbox_form-group">
                                                        <table class="wpfblbox_eachpage">
                                                            <tr>
                                                                <td>
                                                                    <span style="width:60px; float: left;">
                                                                        <input type="radio" id="mobileyes" name="mobile" <?php echo @$mobile[1]; ?> value="1" class="wpfblbox_form-control" style="float:left"><label style="float:left; font-weight: normal;" for="mobileyes">Yes</label>
                                                                    </span>
                                                                    <span style="width:60px; float: left;">
                                                                        <input type="radio" id="mobileno" name="mobile" <?php echo @$mobile[0]; ?> value="0" class="wpfblbox_form-control" style="float:left"><label style="float:left; font-weight: normal;" for="mobileno">No</label>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>                                                                        
                                                </td>
                                            </tr>-->
                                        </table>
                                        <hr>
                                        <table width="100%">
                                            <tr>
                                                <td colspan="2">
                                                    <label class="wpfblbox_control-label">Preview</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 160px; text-align: right; padding-right: 15px;">
                                                    <label>Width</label>
                                                </td>
                                                <td>
                                                    <div style="width:100%; display: none; color: #f00;" id="width_error">The Minimum width is 280px & Max is 500px</div>
                                                    <input onblur="if (!isNumeric(this.value)) {
                                                                    alert('Only digits allowed');
                                                                    this.focus();
                                                                } else {
                                                                    wpfblikebox_func()
                                                                }" type="text" id="width" placeholder="" style="float: left; width: 90%;" name="width" value="<?php echo $myrows[0]->width; ?>" class="wpfblbox_form-control">
                                                               <img src="<?php echo plugins_url("/images/help.png", __FILE__) ?>" style="float:right" help="" title="help">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 160px; text-align: right; padding-right: 15px;">
                                                    <label>Height</label>
                                                </td>
                                                <td>
                                                    <div style="width:100%; display: none; color: #f00" id="height_error">The Minimum height of the Like Box is 130px</div>
                                                    <div class="wpfblbox_form-group">
                                                        <input style="width: 90%; float: left;" onblur="if (!isNumeric(this.value)) {
                                                                        alert('Only digits allowed');
                                                                        this.focus();
                                                                    } else {
                                                                        wpfblikebox_func()
                                                                    }" type="text" id="height" placeholder=""  name="height" value="<?php echo $myrows[0]->height; ?>" class="wpfblbox_form-control" >
                                                    </div>
                                                    <img src="<?php echo plugins_url("/images/help.png", __FILE__) ?>" style="float:right" help1="" title="help1">
                                                </td>
                                            </tr>

                                            <tr>
                                                
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="wpfblbox_form-group" style="text-align: center;">
                                                        <input onchange=" wpfblikebox_func()" <?php
                                                        if (@$myrows[0]->faces == 1) {
                                                            echo 'checked';
                                                        }
                                                        ?> type="checkbox" style="float:left" value="1" name="faces" id="faces"><label style="float:left; line-height: 10px; padding-left: 10px;" for="faces">Show Friends' Faces</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td> 
                                                    <div class="wpfblbox_form-group" style="text-align: center; display: none">
                                                        <input onchange=" wpfblikebox_func()" <?php
                                                        if (@$myrows[0]->header == 1) {
                                                            echo 'checked';
                                                        }
                                                        ?>  type="checkbox" style="float:left" value="1" name="header" id="headers"><label style="float:left; line-height: 10px; padding-left: 10px;" for="headers">Show Header</label>
                                                    </div>
                                                    <div class="wpfblbox_form-group" style="text-align: center;">
                                                        <input onchange=" wpfblikebox_func()" <?php
                                                        if (@$myrows[0]->cover_photo == 1) {
                                                            echo 'checked';
                                                        }
                                                        ?> type="checkbox" style="float:left" value="1" name="cover_photo" id="cover_photo"><label style="float:left; line-height: 10px; padding-left: 10px;" for="cover_photo">Hide Cover Photo</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="wpfblbox_form-group" style="text-align: center; display: none;">
                                                        <input onchange=" wpfblikebox_func()" <?php
                                                        if (@$myrows[0]->border == 1) {
                                                            echo 'checked';
                                                        }
                                                        ?> type="checkbox" style="float:left" value="1" name="border" id="border"><label style="float:left; line-height: 10px; padding-left: 10px;" for="border">Show Border</label>
                                                    </div>
                                                    <div class="wpfblbox_form-group" style="text-align: center">
                                                        <input onchange=" wpfblikebox_func()" <?php
                                                        if (@$myrows[0]->posts == 1) {
                                                            echo 'checked';
                                                        }
                                                        ?>  type="checkbox" style="float:left" value="1" name="posts" id="showposts"><label style="float:left; line-height: 10px; padding-left: 10px;" for="showposts">Show Posts</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td> 
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    
                                                </td>
                                                <td></td>
                                                <td>&nbsp; 
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div id="u_0_18" class="wpfblbox_preview" style="text-align: center"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <hr>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><button type="submit" name="update_wpfblikebox" class="wpfblbox_btn wpfblbox_btn-primary">Save Settings</button></td>
                                                <td colspan="2">
                                                    <div class="wpfblbox_form-group wpfblbox_switch" style="float: right;">
    <?php
    $img = '';
    if ($myrows[0]->status == 0) {
        $img = 'off.png';
    } else {
        $img = 'on.png';
    }
    ?>
                                                        <img onclick="wpfblbox_switchonoff(this)" src="<?php echo plugins_url('/images/' . $img, __FILE__); ?>"> 
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="wpfblbox_col wpfblbox_col-adv" style="width:25%;">
                            <div class="wpfblbox_where">
                                <h2 style="text-align:left;">   
                                    Please support us!
                                </h2>
                                <hr>
                                <div>
                                    <div style="font-family: Georgia,&quot;Times New Roman&quot;,serif;font-style:italic;font-size: 18px; margin-top: 10px;">
                                        Please make a donation:
                                    </div>
                                    <div style="margin-top:0px; margin-bottom: 8px;">
<div style="text-align:center;display:block;font-style:italic;font-family: Verdana,sans-serif;color: #FF0066;font-size: 18px;">
<div style="text-align:center;display:block;font-size: 11px;font-style:italic;font-family: Verdana,sans-serif;color: #FF0066;">
Your donation will help us in providing other useful plugins and <strong>support</strong>.</div>  
  <div style="margin-top:5px;">   
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHJwYJKoZIhvcNAQcEoIIHGDCCBxQCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYBdhMiUQiHIZ5Jrci4MYC3g3UmyrghbZhktvGatnfxUYG5kwOtCcK+hHrpNvzaVTRCEzC+3Fm+lbGsHQ6GLciJRFptFN6JR6myMzLpx4o120UQPyGK2D9N1OtN2Fhb76UIZGfC48OfMdAL3PiYcmLBnh7rTbyv5oYp3IauoVMLNCjELMAkGBSsOAwIaBQAwgaQGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIHYr6eQaQMsqAgYDqAviViskjrIpLyFPwCCwxERUUQJTQXcBxsat9wwpcAqo6v6VPGS2J3i+7fwXg7Z02WKPDUiDQihJ/l+5lWESpV4w1E6+5L4i8xWpBBdj11lYWqOWBJGaHHaWDuF5vZAY+2DpXT3zdWAxR4nZ82BhB16RRw08hKRn9j5hXmfmgOaCCA4cwggODMIIC7KADAgECAgEAMA0GCSqGSIb3DQEBBQUAMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTAeFw0wNDAyMTMxMDEzMTVaFw0zNTAyMTMxMDEzMTVaMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAwUdO3fxEzEtcnI7ZKZL412XvZPugoni7i7D7prCe0AtaHTc97CYgm7NsAtJyxNLixmhLV8pyIEaiHXWAh8fPKW+R017+EmXrr9EaquPmsVvTywAAE1PMNOKqo2kl4Gxiz9zZqIajOm1fZGWcGS0f5JQ2kBqNbvbg2/Za+GJ/qwUCAwEAAaOB7jCB6zAdBgNVHQ4EFgQUlp98u8ZvF71ZP1LXChvsENZklGswgbsGA1UdIwSBszCBsIAUlp98u8ZvF71ZP1LXChvsENZklGuhgZSkgZEwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tggEAMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADgYEAgV86VpqAWuXvX6Oro4qJ1tYVIT5DgWpE692Ag422H7yRIr/9j/iKG4Thia/Oflx4TdL+IFJBAyPK9v6zZNZtBgPBynXb048hsP16l2vi0k5Q2JKiPDsEfBhGI+HnxLXEaUWAcVfCsQFvd2A1sxRr67ip5y2wwBelUecP3AjJ+YcxggGaMIIBlgIBATCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwCQYFKw4DAhoFAKBdMBgGCSqGSIb3DQEJAzELBgkqhkiG9w0BBwEwHAYJKoZIhvcNAQkFMQ8XDTE1MDUwMTExMTgyM1owIwYJKoZIhvcNAQkEMRYEFCDI8mGFnTjXR64qhVAY9PvUulq+MA0GCSqGSIb3DQEBAQUABIGApk8mpCk3INHB+ipyiUC16ZTy1FoYaJH5KZPF2YnL4BQgHCIt/oE79ZG6qiOea917Y//4Z6OPU7IfPjm2yWs1kj6wiOtB/wDiTuT82FQWFNotUIIDihLpei2qRhmaCFDSlJ+ca8qefRyjBtxFt4taXrP56yPWzjUL5yIu4Xa8p2s=-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>


                                 
</div>

                                </div>
                                </div>
                                <hr>
                                <div>
                  
              
                        
<div style="font-family: Georgia,&quot;Times New Roman&quot;,serif;font-style:italic;font-size: 18px; margin-top: 10px;">
                                        Visit support for any issues:
                                    </div>
                                    <div style="margin-top:10px; margin-bottom: 8px;">
<div style="text-align: center;">
                                        <a href="http://wordpress.org/support/plugin/facebook-page-like-popup-box" target="_blank" class="wpfblbox_btn wpfblbox_btn-success" style="width:90%; margin-top:5px; margin-bottom: 5px; ">Support!</a>
                                    </div>
                                    </div>
                                    
                                </div>
                                <hr>
                                <div>                  
                                    
                                </div>
                            </div>
                        </div>
                        <div class="wpfblbox_col wpfblbox_col-adv">

                        </div>
                        <div class="wpfblbox_clearfix"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </form> 
    
    <?php
}

//-------------------------------------- database --------------------
global $fbpopup_wpfblbox_db_version;
$fbpopup_wpfblbox_db_version = '1.1';

function wpfblbox_install() {
    global $wpdb;
    global $fbpopup_wpfblbox_db_version;

    $table_name = $wpdb->prefix . 'wpfblbox';

    $charset_collate = $wpdb->get_charset_collate();

    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}$table_name");

    // status: 1=active, 0 unactive
    // display: 1=all other page, 2= home page, 3=all pages

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
                display int, 
                width int,
                height int,
                except_ids varchar(255),
                fbpage varchar (250),
                when_display int,
                users int,
                delay int,
                mobile int,
                color varchar (50),
                faces int,
                header int,
                border int,
                posts int,
                cover_photo int,
                status int, 
                user_id int,
                active int,
		created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		last_modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

    add_option('fbpopup_wpfblbox_db_version', $fbpopup_wpfblbox_db_version);
}

function wpfblikebox_myplugin_update_db_check() {
    global $fbpopup_wpfblbox_db_version;
    if (get_site_option('fbpopup_wpfblbox_db_version') != $fbpopup_wpfblbox_db_version) {
        wpfblbox_install_data();
    }
}

add_action('plugins_loaded', 'wpfblikebox_myplugin_update_db_check');

function wpfblbox_install_data() {
    global $wpdb;

    $type = '0';
    $radio_value = 'text';
    $data = 'Congratulations, you just completed the installation. Welcome to Jazz Popup!';

    $table_name = $wpdb->prefix . 'wpfblbox';

    $ul = '0';
    global $current_user;
    get_currentuserinfo();
    if (isset($current_user)) {
        $ul = $current_user->ID;
    }
    $user_id = $ul;
    $table = $wpdb->prefix . 'wpfblbox';
    $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    if ($myrows == NULL) {
        $wpdb->insert(
                $table_name, array(
            'created' => current_time('mysql'),
            'last_modified' => current_time('mysql'),
            'status' => 1,
            'display' => 7,
            'width' => 340,
            'height' => 500,
            'except_ids' => '',
            'fbpage' => 'https://www.facebook.com/wordpress',
            'when_display' => 1,
            'users' => 2,
            'delay' => 0,
            'mobile' => 0,
            'color' => 'light',
            'cover_photo' => 0,        
            'faces' => 1,
            'header' => 1,
            'border' => 1,
            'posts' => 1,
            'user_id' => $user_id,
            'active' => 1,
                )
        );
    }
}

register_activation_hook(__FILE__, 'wpfblbox_install');
register_activation_hook(__FILE__, 'wpfblbox_install_data');

//--------------------------------------------------------------------
function wpfblbox_my_enqueue($hook) {
    //only for our special plugin admin page
    wp_register_style('wpfblbox_css', plugins_url('/css/wpfblbox_style.css', __FILE__));
    wp_enqueue_style('wpfblbox_css');
    wp_register_style('wpfblbox_magicsuggest-min', plugins_url('/css/magicsuggest-min.css', __FILE__));
    wp_enqueue_style('wpfblbox_magicsuggest-min');
    wp_register_style('wpfblbox_jquery-ui', plugins_url('/css/jquery-ui.css', __FILE__));
    wp_enqueue_style('wpfblbox_jquery-ui');
}

add_action('admin_enqueue_scripts', 'wpfblbox_my_enqueue');
add_action('admin_enqueue_scripts', 'wpfblbox_my_admin_scripts');

function wpfblbox_my_admin_scripts() {
    if (isset($_GET['page']) && $_GET['page'] == 'wp_facebook-like-box') {
        wp_enqueue_media();
        wp_register_script('my-admin-js', plugins_url('/js/wpfblbox.js', __FILE__), array('jquery'));
        wp_enqueue_script('my-admin-js');
        wp_register_script('wpfblbox_magicsuggest', plugins_url('/js/magicsuggest-min.js', __FILE__), array('jquery'));
        wp_enqueue_script('wpfblbox_magicsuggest');
        wp_enqueue_script('jquery-ui-tooltip');
    }
}
?>