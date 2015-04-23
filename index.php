<?php 
/*
   Plugin Name: Facebook Page Like Popup Box
   Plugin URI: 
   Description: All your visitors should know about your facebook page and tell their friends.This plugin is made from new facebook page plugin.
   Version: 1.0
   Author: Tayyab
   Author URI:http://itayyab.blogspot.com 
   Copyright: 2015, Tayyab
*/

require(dirname(__FILE__) .'/includes/class-moscow.php');
require(dirname(__FILE__) .'/class-activate.php');


if (is_admin() ){
	require(dirname(__FILE__) .'/admin.php');
	$arvlbAdmin 		= new arvlbAdmin();
} else {
  $arvlbGASuite = new arvlbFPPL();
}

/**
 * Main plugin class. 
 */
class arvlbFPPL 
{
    private $options = null;

    /**
     * Constructor, initializes options
     */
    function __construct(){
      $this->options = get_option('arv_fb24_opt',arvlbSHARED::getDefaults() );
      add_action('wp_enqueue_scripts', array($this,'addScript'));

    }

    /**
     * Add all scripts required on the front-end
     */
    public function addScript(){
      $o  = $this->options;

    if ((is_front_page()  && !empty($o['display_on_homepage']) )
      || (is_archive()  && !empty($o['display_on_archive']))
      || (is_single()   && !empty($o['display_on_post']))
      || (is_page()     && !empty($o['display_on_page']))
        ){

    

    wp_register_style('arevico_scsfbcss', plugins_url( 'includes/front/scs/scs.css',__FILE__));
    wp_enqueue_style( 'arevico_scsfbcss'); 

    wp_register_script('arevico_scsfb', plugins_url( 'includes/front/scs/scs.js',__FILE__),array('jquery'));
    wp_enqueue_script ('arevico_scsfb');


    wp_register_script('arevico_scsfb_launch',  plugins_url( 'includes/front/js/launch.js',__FILE__),array('jquery'));
    wp_enqueue_script ('arevico_scsfb_launch');
    wp_localize_script('arevico_scsfb_launch','lb_l_ret',arvlbSHARED::normalize($o));

    }
  }

  
 } // end of main plugin class


    /**
     * Function called on activation of the plugin
     */
    function arvlb_arv_activate() {
      arvlbActivate::on_activate();
    }

    /**
     * Function called on de-activation of the plugin
     */    
    function arvlb_arv_deactivate() {
      arvlbActivate::on_deactivate();
    }

  register_activation_hook( __FILE__, 'arvlb_arv_activate' );
  register_uninstall_hook(__FILE__, 'arvlb_arv_deactivate' );
    
    

/**
 * This class contains shared common properties and/or methods
 */
class arvlbSHARED{
  //Defaults for the option table of this plugin
  public static $defaults = array (
  'fb_id'         => '',
  'delay'         => '2000',
  'show_once'       => '0',
  'display_on_homepage'   => '1',
  'coc'         => '0',
  'cooc'          => '1'  );


  /**
   * Normalize settings to prevent undefined errors on the front-end
   */
  public static function normalize($o){
    $checks = array(
      'width'		=> '400',
      'height'		=> '255',
      'delay'		=> '0',
      'coc'         => '0',
      'fb_id'		=> '' ,
      'cooc'        => '0'
    );

    

    return array_merge($checks,$o);
  }

  
  public static function getDefaults(){
    $o = self::$defaults;
    if (empty($o['install_date']))
      $o['install_date'] = time();

    return $o;
  }
  
}

 ?>