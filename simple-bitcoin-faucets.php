<?php
/*
 * Plugin Name: Bitcoin Satoshi Tools
 * Plugin URI: http://gra4.com
 * Description: Satoshi Bonds, Games, Faucets, Visitor Rewarder, Referral Program, and more...
 * Author: Alexey Trofimov
 * Version: 1.7.0  
 * Author URI: http://gra4.com
 * Text Domain: simple-bitcoin-faucets
 * License: GPLv2
 * Domain Path: /languages/
*/
global $Simple_Bitcoin_Faucets_self_version;
$Simple_Bitcoin_Faucets_self_version = "1.4.0";
/*
This Plugin utilizes Remotely Hosted Faucets provided by https://wmexp.com
*/

include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'bonds/bonds.php'); //SBF_DB_ stuff
include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'paidcontent/paidcontent.php'); //SBF_DB_ stuff
include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'referral/referral.php'); //SBF_CM_referral_visit_process
include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'videopoker/poker_WP.php'); //SBFG_WP_get_poker_init
include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'paidcontent/paidcontent_WP.php'); //SBF_WP_get_paidcontent_init


$Simple_Bitcoin_Faucets_Options_str = 'SBFO';

function Simple_Bitcoin_Faucets_load_plugin_textdomain() {
    load_plugin_textdomain( 'simple-bitcoin-faucets', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'Simple_Bitcoin_Faucets_load_plugin_textdomain' );



class Simple_Bitcoin_Faucets_Plugin {
	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'wp_head', array( $this, 'insert_rewarder' ) );
		
		add_action( 'user_register', array( $this, 'user_registered' ) ); //referral
		add_action( 'wp_login', array( $this, 'user_login' ) , 10, 2); //referral
		
		add_filter( "plugin_action_links_" . plugin_basename(  __FILE__ ), array( $this,'add_settings_link') );
		add_shortcode('SBFS', array( $this, 'faucets_shortcode') );
		add_shortcode('SBFR', array( $this, 'reward_shortcode') );
		add_shortcode('SBFG_BLOCKRAIN', array( $this, 'blockrain_shortcode') );
		add_shortcode('SBFG_2048', array( $this, 'g2048_shortcode') );
		add_shortcode('SBFG_LINES', array( $this, 'lines_shortcode') );
		add_shortcode('SBFG_MINESWEEPER', array( $this, 'minesweeper_shortcode') );
		add_shortcode('SBFG_VIDEOPOKER', array( $this, 'videopoker_shortcode') );
		add_shortcode('SBFG_BUBBLESHOOTER', array( $this, 'bubbleshooter_shortcode') );
		add_shortcode('SBFG_REF_LINK_CONSTRUCTOR', array( $this, 'referral_link_constructor_shortcode') );		
		add_shortcode('SBFG_REF_SINGUP_BONUS', array( $this, 'referral_signup_bonus_shortcode') );	
		add_shortcode('SBFG_REF_VISITS_BONUS', array( $this, 'referral_visits_bonus_shortcode') );	
		add_shortcode('SBFG_REF_VISITS_PAGES', array( $this, 'referral_visits_pages_shortcode') );	
		
		add_shortcode('REF_SESSION', array( $this, 'referral_session') );
		add_shortcode('REF_LOG', array( $this, 'referral_log') );		
		
		add_shortcode('SBFG_BOND_REDEEM', array( $this, 'render_bonds_box') );	

		add_shortcode('SBF_CONTENT_UNPAID', array( $this, 'render_content_unpaid') );
		add_shortcode('SBF_CONTENT_PAID', array( $this, 'render_content_paid') );
		add_shortcode('SBF_CONTENT_PAY_PROMPT', array( $this, 'render_content_pay_prompt') );
		
//		add_filter( 'after_setup_theme', 'programmatically_create_post' );
	}

	function render_content_unpaid($atts = [], $content = null, $tag = '')
	{
		return SBF_C_unpaid($atts, $content, $tag );
	}
	function render_content_paid($atts = [], $content = null, $tag = '')
	{
		return SBF_C_paid($atts, $content, $tag);
	}	
	function render_content_pay_prompt($atts = [], $content = null, $tag = '')
	{
		return SBF_C_pay_prompt($atts, $content, $tag);
	}	
	
	function render_bonds_box()
	{
		return SBF_DB_render_bonds_box();
	}
	
	function referral_log()
	{
		return print_r(get_transient( 'SBF_CM_REFERRAL_LOG' ),true);
	}
	
	function referral_session()
	{
		return print_r($_SESSION,true);
	}	
	
	function referral_link_constructor_shortcode( )	
	{	
		return(SBF_CM_referral_create_link_constructor() ); //referral/referral.php
	}
	
	function referral_signup_bonus_shortcode( )	
	{
		return( esc_attr( get_option('sfbg_referral_registration_bonus','50') ) );
	}
	function referral_visits_bonus_shortcode( )	
	{
		return( esc_attr( get_option('sfbg_referral_visit_bonus','10') ) );
	}
	function referral_visits_pages_shortcode( )	
	{
		return( esc_attr( get_option('sfbg_referral_visit_pages','5') ) );
	}
	
	
	
	
	function insert_rewarder( )	
	{
		if(get_option('sfbr_include_all_pages') == 'on')
		{
			echo($this->reward_shortcode( 0 ));
		}
	}
	
	
	function faucets_shortcode( $atts )
	{
		$fid = $atts['fid'];
		$ret = "\n<div id='wmexp-faucet-$fid'></div><script src='https://wmexp.com/faucet/$fid/'></script>\n";
		return($ret);
	}
	
	function blockrain_shortcode_localize()
	{
		$ret = '';
		$ret .= "\n var sfbg_br_t1 = '" . __('Wanna play for Satoshi?','simple-bitcoin-faucets') ."';";
		$ret .= "\n var sfbg_br_t2 = '" . __('Play','simple-bitcoin-faucets') ."';";
		$ret .= "\n var sfbg_br_t3 = '".__('Bonus is yours!','simple-bitcoin-faucets') ."';";
		$ret .= "\n var sfbg_br_t4 = '".__('Score','simple-bitcoin-faucets') ."';";
		$ret .= $this->main_js_shortcode_localize();
		return($ret);
	}
	
	function main_js_shortcode_localize() 
	{
		$ret = '';
		$ret .= "\n var sfbg_main_ready = '".__('Ready for the prize?','simple-bitcoin-faucets') ."';";
		$ret .= "\n var sfbg_main_no = '".__('No thanks','simple-bitcoin-faucets') ."';";
		$ret .= "\n var sfbg_main_yes = '".__('Yes please!','simple-bitcoin-faucets') ."';";
		$ret .= "\n var sfbg_main_score = '".__('Score','simple-bitcoin-faucets') ."';";
		$ret .= "\n var sfbg_main_confirm = '".__('Are you sure','simple-bitcoin-faucets') ."';";
		$ret .= "\n var sfbg_main_delete = '".__('Delete','simple-bitcoin-faucets') ."';";
		$ret .= "\n var sfbg_main_add = '".__('Add','simple-bitcoin-faucets') ."';";
		return($ret);
	}	
	
	function g2048_shortcode_localize() 
	{
		$ret = '';
		$ret .= $this->main_js_shortcode_localize();
		return($ret);
	}	
	
	function lines_shortcode_localize() 
	{
		$ret = '';
		$ret .= $this->main_js_shortcode_localize();
		return($ret);
	}	
	
	function minesweeper_shortcode_top() 
	{
		global $Simple_Bitcoin_Faucets_self_version;
		$ret = '';
		$ret .= "\n<link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "minesweeper/css/minesweeper.css'> ";
		$ret .= "\n<link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "minesweeper/css/smoothness/jquery-ui-1.9.2.custom.min.css'>";
		$ret .= "\n".'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">';
		$ret .= "\n".'<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>';	
		$ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.css?ver=$Simple_Bitcoin_Faucets_self_version'>";   
		$ret .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.js?ver=$Simple_Bitcoin_Faucets_self_version'></script>";  
		$ret .= "\n<script src='" . plugin_dir_url( __FILE__ ) . "minesweeper/js/MineSweeper.js'></script>";
		return($ret);
	}	
	
	function minesweeper_shortcode_body() 
	{
		$ret = '';
		$ret .= "\n";
		$ret .= "\n<div id='sfbg_minesweeper_game_faucet_wrap' class='flexblock' style='width:500px;'>";	
		$ret .= "\n<div id='sfbg_minesweeper_faucet-TO-BE' style='display:none;min-width:400px;min-height:400px;'></div>";	
		$ret .= "\n<center><div id='sfbg_minesweeper_game'></div></center>";	
		$ret .= "\n</div>";		
		$ret .= "\n<script src='" . plugin_dir_url( __FILE__ ) . "minesweeper/starter.js'></script>";	
		return($ret);
	}
	
	function minesweeper_shortcode_localize() 
	{
		$ret = '';
		$ret .= "\n<script>";
		$ret .= "\n	var sbfg_web_path='" . plugin_dir_url( __FILE__ ) . "';";//path to worker file
		$ret .= "\n	var sbfg_time_txt='" . __('Time','simple-bitcoin-faucets') . "';";
		$ret .= "\n	var sbfg_newgame_txt='" . __('New Game','simple-bitcoin-faucets') . "';";	
		$ret .= "\n	var sbfg_beginner_txt='" . __('Beginner','simple-bitcoin-faucets') . "';";		
		$ret .= "\n	var sbfg_intermediate_txt='" . __('Intermediate','simple-bitcoin-faucets') . "';";
		$ret .= "\n	var sbfg_expert_txt='" . __('Expert','simple-bitcoin-faucets') . "';";
		$ret .= "\n</script>";		
		return($ret);
	}	
	
	function minesweeper_shortcode( $atts )
	{
		$ret = '';
		$ret .= "\n<script>";
		$ret .= $this->main_js_shortcode_localize();
		$ret .= "\n</script>";
		$ret .= "\n <div style='display:none' sbf_game_settings='minesweeper'>".get_option('sfbg_sf_minesweeper','beginner:123456,intermediate:123456,expert:123456')."</div>";
		$ret .= $this->minesweeper_shortcode_localize();
		$ret .= $this->minesweeper_shortcode_top();
		$ret .= $this->minesweeper_shortcode_body();
		return($ret);		
	}


	function bubbleshooter_shortcode_top() 
	{
		global $Simple_Bitcoin_Faucets_self_version;
		$ret = '';
		$ret .= "\n".'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">';
		$ret .= "\n".'<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>';	
		$ret .= "\n<script src='" . plugin_dir_url( __FILE__ ) . "bubbleshooter/bubbleshooter.js'></script>";
		$ret .= "\n<link href='" . plugin_dir_url( __FILE__ ) . "bubbleshooter/bubbleshooter.css' rel='stylesheet'>";
		$ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.css?ver=$Simple_Bitcoin_Faucets_self_version'>";   
		$ret .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.js?ver=$Simple_Bitcoin_Faucets_self_version'></script>";  		
		return($ret);
	}	
	
	function bubbleshooter_shortcode_body() 
	{
		$ret = '';
		$ret .= "\n";
		$ret .= '<div class="sfbg_bubbleshooter_game_wrap" style="border:0px dotted gray;">';
		$ret .= '<div id="sfbg_bubbleshooter_game" class="sfbg_bubbleshooter_game" style="width:600px; height:600px;">';
		$ret .= "<canvas id='bubbleshooter_viewport' width='628' height='628' style='width:600px; height:auto; border:0px solid red;'></canvas>";
		$ret .= '</div>';
		$ret .= '<div id="sfbg_bubbleshooter_faucet-TO-BE" style="display:none;width:600px;min-height:400px;"></div>';
		$ret .= '</div>';
		$ret .= "\n<script src='" . plugin_dir_url( __FILE__ ) . "bubbleshooter/starter.js'></script>";
		return($ret);
	}
	
	function bubbleshooter_shortcode_localize() 
	{
		$ret = '';
		$ret .= "\n<script>";
		$ret .= $this->main_js_shortcode_localize();
		$ret .= "\n var bubbleshooter_images = '" .plugin_dir_url( __FILE__ ) . "bubbleshooter/bubble-sprites.png';";			
		$ret .= "\n	var bubbleshooter_title_txt='" . __('Bubble Shooter Game','simple-bitcoin-faucets') . "';";		
		$ret .= "\n	var bubbleshooter_gamover_txt='" . __('Game Over!','simple-bitcoin-faucets') . "';";		
		$ret .= "\n	var bubbleshooter_clicktostart_txt='" . __('Click to start','simple-bitcoin-faucets') . "';";
		$ret .= "\n	var bubbleshooter_score_txt='" . __('Score','simple-bitcoin-faucets') . "';";		
		$ret .= "\n	var bubbleshooter_loaded_txt='" . __('Loaded','simple-bitcoin-faucets') . "';";		
		
		$ret .= "\n</script>";		
		return($ret);
	}	
	
	function bubbleshooter_shortcode( $atts )
	{
		$ret = '';
		$ret .= $this->bubbleshooter_shortcode_localize();
		$ret .= $this->bubbleshooter_shortcode_top();
		$ret .= "\n <div style='display:none' sbf_game_settings='bubbleshooter'>".get_option('sfbg_sf_bubbleshooter','1000:123456,5000:123456,10000:123456,20000:123456,40000:123456,60000:123456')."</div>";
		$ret .= $this->bubbleshooter_shortcode_body();
		return($ret);		
	}



	
	function referral_shortcode_top() 
	{
	}
	function referral_shortcode_body() 
	{
	}
	function referral_shortcode_localize() 
	{
	}
	function referral_shortcode( $atts )
	{
	}	
	
	function videopoker_shortcode_top() 
	{
		$ret = '';
		
		$ret .= "\n<link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css'>";
		$ret .= "\n<script src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>";
		$ret .= "\n<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'>";	

		//do it once, do it here so can be defined in standalone version, poker_util.php
		if(!function_exists ( 'poker_get_main_url' )) 
		{
			function poker_get_main_url() //
			{
				return(plugin_dir_url( __FILE__ ).'videopoker/'); 
			}
		}
		
		include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'videopoker/poker_get_settings.php');
		include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'videopoker/poker_util.php');
		$ret .= settings_to_js();
		if($stop_if_adblock)
			$ret .= "\n<script src='" . plugin_dir_url( __FILE__ ) . "sbf_lib/advertisment.js'></script>\n";
		include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'videopoker/poker_lang_WP.php');	
		$ret .= poker_text_to_js();		

		$ret .= "\n<link href='" . plugin_dir_url( __FILE__ ) . "sbf_lib/messagebox.css' rel='stylesheet'>";
		$ret .= "\n<script src='" . plugin_dir_url( __FILE__ ) . "sbf_lib/messagebox.js'></script>";
		$ret .= "\n<link href='" . plugin_dir_url( __FILE__ ) . "videopoker/poker.css' rel='stylesheet'>";
		$ret .= "\n<script src='" . plugin_dir_url( __FILE__ ) . "videopoker/poker.js'></script>	";
		$ret .= "\n<script src='" . plugin_dir_url( __FILE__ ) . "videopoker/poker_util.js'></script>";		
		return($ret);
	}	
	
	function videopoker_shortcode_body() 
	{
		$ret = '';
		$ret .= "\n";
		include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'videopoker/poker_WP.php');	
		SBFG_WP_poker_settings_to_session();
		$ret .= SBFG_WP_get_poker_body();
		return($ret);
	}
	
	function videopoker_shortcode_localize() 
	{
		$ret = '';
		return($ret);
	}	
	
	function videopoker_shortcode( $atts )
	{
		$ret = '';
		$ret .= $this->videopoker_shortcode_localize();
		$ret .= $this->videopoker_shortcode_top();
		$ret .= $this->videopoker_shortcode_body();
		return($ret);		
	}
	
	
	function reward_shortcode_localize()
	{
		$ret = '';
		$ret .= "\n RemoteFaucetSurferReward.txt_bonus = '" . __('bonus','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_click_bonus = '" . __('Get your bonus!','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_more_pages = '" . __('Visit %n more pages for the bonus','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_close = '" . __('close','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_info = '" . __('info','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_loading = '" . __('loading','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_shown = '" . __('faucet shown in other window','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_discard_confirm = '" . __('Bonus will be discarded!\n\nAre you sure?','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_thanks_visits = '" . __('<b>Thanks for visiting %n pages!</b>','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_more_seconds = '" . __('%n more seconds to count the page','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_already_visited = '" . __('Already visited!','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_off_now = '" . __('Close for now','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_off_day = '" . __('Off for day','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_off_week = '" . __('Off for week','simple-bitcoin-faucets') ."';";
		$ret .= "\n RemoteFaucetSurferReward.txt_off_month = '" . __('Off for month','simple-bitcoin-faucets') ."';";
		return($ret);
	}
	

	
	
	function reward_shortcode( $atts )
	{
		$ret = '';
		$ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "rewarder/wme_rfsr.css'>";   
		$ret .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "rewarder/wme_rfsr.js'></script>";  
		$ret .= "\n <script>";
		$ret .= $this->reward_shortcode_localize();
		$ret .= "\n RemoteFaucetSurferReward.faucet_id = " . get_option('sfbr_faucet_id','123456') . ";" ;
		$ret .= "\n RemoteFaucetSurferReward.box_size = '" . get_option('sfbr_mark_size','40') . "px';" ;
		$ret .= "\n RemoteFaucetSurferReward.allow_reloads = " . get_option('sfbr_allow_reloads','false') . ";" ;	
		$ret .= "\n RemoteFaucetSurferReward.allow_repeats = " . get_option('sfbr_allow_repeats','false') . ";" ;
		$ret .= "\n RemoteFaucetSurferReward.pages_to_visit = " . get_option('sfbr_pages_to_visit','10') . ";" ;
		$ret .= "\n RemoteFaucetSurferReward.seconds_on_page = " . get_option('sfbr_seconds_on_page','5') . ";" ;
		$ret .= "\n RemoteFaucetSurferReward.vertical_side = '" . get_option('sfbr_mark_v_position','top') . "';"; 
		$ret .= "\n RemoteFaucetSurferReward.vertical_offset = '" . get_option('sfbr_mark_v_offset','30') . "px';" ;
		$ret .= "\n RemoteFaucetSurferReward.horizontal_side = '" . get_option('sfbr_mark_h_position','left') . "';";  
		$ret .= "\n RemoteFaucetSurferReward.horizontal_offset = '" . get_option('sfbr_mark_h_offset','30') .  "px';" ; 
		$ret .= "\n RemoteFaucetSurferReward.faucet_extra_styles='width:500px;';";
		$ret .= "\n </script>";
		return($ret);
	}
	
	function blockrain_shortcode( $atts )
	{
		global $Simple_Bitcoin_Faucets_self_version;
		$ret = '';
		$ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "blockrain/blockrain.css'>";   
		$ret .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "blockrain/blockrain.jquery.js'></script>";  
		$ret .= "\n".'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">';
		$ret .= "\n".'<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>';	
		$ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.css?ver=$Simple_Bitcoin_Faucets_self_version'>";   
		$ret .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.js?ver=$Simple_Bitcoin_Faucets_self_version'></script>";  
		$ret .= "\n <div style='display:none' sbf_game_settings='blockrain'>".get_option('sfbg_sf_blockrain','1000:123456,5000:123456,10000:123456,20000:123456,30000:123456,40000:123456,50000:123456,70000:123456')."</div>";
		$ret .= "\n <script>";
		$ret .= $this->blockrain_shortcode_localize();
		$ret .= "\n </script>";
		$ret .= "\n".'<div class="sfbg_br_game_wrap" style="min-width:400px; min-height:500px;"><center>';
		$ret .= "\n".'<div class="sfbg_br_game" style="width:250px; height:500px;"></div>';
		$ret .= "\n".'<div id="sfbg_br_faucet-TO-BE" style="display:none;min-width:400px;min-height:400px;"></div></center></div>';	
		$ret .= "\n".'<script src="' . plugin_dir_url( __FILE__ ) . 'blockrain/starter.js"></script>';	
		
		return($ret);		
	}

	function g2048_shortcode( $atts )
	{
		global $Simple_Bitcoin_Faucets_self_version;
		$ret = '';
		$ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "2048/css/jquery.2048.css'>";   
		$ret .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "2048/js/jquery.2048.js'></script>";  
		$ret .= "\n".'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">';
		$ret .= "\n".'<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>';	
		$ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.css?ver=$Simple_Bitcoin_Faucets_self_version'>";   
		$ret .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.js?ver=$Simple_Bitcoin_Faucets_self_version'></script>";  
		$ret .= "\n <div style='display:none' sbf_game_settings='2048'>".get_option('sfbg_sf_2048','64:123456,128:123456,256:123456,512:123456,1024:123456,2048:123456')."</div>";		
		$ret .= "\n <script>";
		$ret .= $this->g2048_shortcode_localize();
		$ret .= "\n </script>";
		$ret .= "\n".'<div class="sfbg_2048_game_wrap" style="min-width:300px; min-height:300px;"><center>';
		$ret .= "\n".'<div class="2048container text-center" id="sfbg_2048_game"></div>';
		$ret .= "\n".'<div id="sfbg_2048_faucet-TO-BE" style="display:none;min-width:400px;min-height:400px;"></div></center></div>';	
		$ret .= "\n".'<script src="' . plugin_dir_url( __FILE__ ) . '2048/starter.js"></script>';	
		
		return($ret);		
	}

	function lines_shortcode( $atts )
	{
		global $Simple_Bitcoin_Faucets_self_version;
		$ret = '';
		$ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "lines/lines.css'>";   
		$ret .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "lines/lines.js'></script>";  
		$ret .= "\n".'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">';
		$ret .= "\n".'<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>';	
		$ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.css?ver=$Simple_Bitcoin_Faucets_self_version'>";   
		$ret .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.js?ver=$Simple_Bitcoin_Faucets_self_version'></script>";  
		$ret .= "\n <div style='display:none' sbf_game_settings='lines'>".get_option('sfbg_sf_lines','10:123456,50:123456,150:123456,300:123456,450:123456')."</div>";		
		$ret .= "\n <script>";
		$ret .= $this->lines_shortcode_localize();
		$ret .= "\n </script>";
		$ret .= "\n".'<div class="sfbg_ln_game_wrap" style="border:0px dotted gray;">';
		$ret .= "\n".'<div id="sfbg_ln_game" class="sfbg_ln_game" >';
		$ret .= "\n".'	<center><div id="game" style="width:450px">';
		$ret .= "\n".'			<div class="sfbg_ln_score">' . __( 'Score', 'simple-bitcoin-faucets' ) . ' : <strong class="score">0</strong></div>';
		$ret .= "\n".'			<div class="forecast sfbg_ln_forecast"></div>';
		$ret .= "\n".'		<div class="grid"></div>';		
		$ret .= "\n".'	</div></center>';
		$ret .= "\n".'</div>';
		$ret .= "\n".'</div>';
		$ret .= "\n".'<div id="sfbg_ln_faucet-TO-BE" style="display:none;min-width:400px;min-height:400px;"></div></div>';		
		$ret .= "\n".'<script src="' . plugin_dir_url( __FILE__ ) . 'lines/starter.js"></script>';	
		return($ret);		
	}	
	
	
	function check_options(){
        if(!get_option('plugin_abbr_op')) {
            //not present, so add
            $op = array(
                'key' => 'value',
            );
            add_option('plugin_abbr_op', $op);
        }	
	}
	
	
	function init() {
		$this->options = array_merge( 
		array(
			'counter-code' => '',
		), (array) get_option( 'simple-bitcoin-faucets', array() ) );
//building params for SBF_CM_referral_process($bcnref,$r_apikey,$r_bonus,$v_apikey,$v_bonus,$v_pages)	
		$bcnref = isset($_GET['BTCREF'])?$_GET['BTCREF']:'';
		$v_apikey = get_option('sfbg_referral_visits_api_key','');
		$v_bonus = get_option('sfbg_referral_visit_bonus','10');
		$v_pages = get_option('sfbg_referral_visit_pages','5');
		$v_interval = get_option('sfbg_referral_visit_time','30');
		$v_forbidden_a = get_option('sfbg_referral_forbidden_bitcoin_addresses','');
		$v_forbidden_ip = get_option('sfbg_referral_forbidden_ip_addresses','');
		SBF_CM_referral_visit_process($bcnref,$v_apikey,$v_bonus,$v_pages,$v_interval,$v_forbidden_a,$v_forbidden_ip);
		SBFG_WP_get_poker_init();
		SBF_WP_get_paidcontent_init();
	}
	
	function user_registered($user_id, $password='', $meta=array())  {
		$bcn_addr_referrer = $_SESSION['BCNREF_ADDR'];
		update_user_meta( $user_id, 'BCNREF_ADDR', $bcn_addr_referrer );    
	}
	
	function user_login( $user_login, $user){
		$user_id = $user->ID;
		$bcn_addr_referrer = get_user_meta( $user_id, 'BCNREF_ADDR')[0];
		delete_user_meta( $user_id, 'BCNREF_ADDR');
		if( (SBF_CM_referral_is_valid_address($bcn_addr_referrer))  )
		{
			$_SESSION['BCNREF_ADDR'] = $bcn_addr_referrer; //for log
			$r_apikey = get_option('sfbg_referral_register_api_key','');
			$r_bonus = get_option('sfbg_referral_registration_bonus','50');
			if( (strlen($r_apikey) == 40) && ($r_bonus > 0) ) //configured
			{
				SBF_CM_referral_send($r_apikey,$bcn_addr_referrer,$r_bonus);	
				SBF_CM_referral_log($r_bonus,'R');
			}
			unset($_SESSION['BCNREF_ADDR']);//just in case
		}
	}

	function admin_init() {
//		$this->check_options();
		$extra_page_content = __( 'Use plugin `Per page head` to create separate favicon for this page, so it will look attractive in the Faucet Lists', 'simple-bitcoin-faucets' );
		$extra_page_content = "\n<!-- ".$extra_page_content." -->";
		if(isset($_GET['fid']))
		{
			$get_t = sanitize_text_field($_GET['t']); //trust noone!
			$get_fid = sanitize_text_field($_GET['fid']); //espacially here
			$template_path = dirname( __FILE__ ) . '/templates/template' . $get_t . '.php';
//die($template_path);			
			$page_content = file_get_contents($template_path);
			$page_content = str_replace('{{{FAUCET_ID}}}',$get_fid,$page_content);
			$page_title = 'Bitcoin Faucet ' . $get_fid;
			if(isset($_GET['title']))
			{
				$page_title = $_GET['title'];
			}
			$post_id = $this->programmatically_create_post($page_title , $page_content . $extra_page_content);
			if( is_wp_error($post_id) ){
				echo $post_id->get_error_message();
			}
			$url = get_permalink($post_id);
			echo("<script>top.location.href='$url';</script>");
			wp_die($url);
		}
		if(isset($_GET['shortcode']))
		{
			$get_name = sanitize_text_field($_GET['name']); //trust noone!
			$get_shortcode = sanitize_text_field($_GET['shortcode']); //trust noone!
			$post_id = $this->programmatically_create_post($get_name,$get_shortcode . $extra_page_content);
			if( is_wp_error($post_id) ){
				echo $post_id->get_error_message();
			}
			$url = get_permalink($post_id);
			echo("<script>top.location.href='$url';</script>");
			wp_die($url);
		}
		
	}

	function programmatically_create_post($ini_title, $content) {

		$new_page_title = $ini_title; //'Bitcoin Faucet';
		
        $page_check = get_page_by_title($new_page_title);
		if(isset($page_check->ID))
		{
			$count = 1;
			while(isset($page_check->ID))
			{
				$new_page_title = $ini_title . '-' . $count;
				$page_check = get_page_by_title($new_page_title);
				$count++;
			}
		}
			
		$new_page_content = wp_slash($content);
        $new_page = array(
                'post_type' => 'page',
                'post_title' => $new_page_title,
                'post_content' => $new_page_content,
                'post_status' => 'draft',
                'post_author' => 1,
        );
        $new_page_id = wp_insert_post($new_page);
		return($new_page_id);
	} // end programmatically_create_post	
	
	
	function register_all_setings()
	{
		global $Simple_Bitcoin_Faucets_Options_str;
//Rewarder options
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbr_faucet_id' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbr_include_all_pages' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbr_mark_size' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbr_mark_h_offset' );	
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbr_mark_v_offset' );	
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbr_mark_h_position' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbr_mark_v_position' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbr_pages_to_visit' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbr_seconds_on_page' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbr_allow_repeats' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbr_allow_reloads' );

//Score/Faucet Options	START	
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_bubbleshooter' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_blockrain' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_2048' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_lines' );
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_minesweeper' );
//Score/Faucet Options	END

//Video Poker Options	START
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_videopoker_api_key'); 
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_videopoker_maximum_bet'); 
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_videopoker_minimum_initial_bonus'); 
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_videopoker_maximum_initial_bonus'); 
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_videopoker_bonuses_before_deposit');
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_videopoker_wins_before_withdraw'); 
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_videopoker_maximum_deposit'); 
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_videopoker_minimum_deposit'); 
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_videopoker_balance_page_leave_confirm');
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_sf_videopoker_stop_if_adblock'); 
//Video Poker Options	END

//Referral	START
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_referral_register_api_key'); 	
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_referral_visits_api_key'); 	
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_referral_registration_bonus'); 
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_referral_visit_bonus'); 
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_referral_visit_pages'); 
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_referral_visit_time');
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_referral_forbidden_bitcoin_addresses'); 
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_referral_forbidden_ip_addresses'); 
//Referral	END

//Bonds	START
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_bonds_api_key'); 	
		SBF_DB_prepare_db_tables(); //we are in admin menu, current user is admin
//Bonds	END

//PaidContent	START
		register_setting( $Simple_Bitcoin_Faucets_Options_str, 'sfbg_paidcontent_api_key'); 	
//PaidContent	END

	}//register_all_setings()
	
	
	function admin_menu() {
		add_options_page( __( 'Bitcoin Satoshi Tools', 'simple-bitcoin-faucets' ), __( 'Bitcoin Satoshi Tools', 'simple-bitcoin-faucets' ), 'manage_options', 'simple-bitcoin-faucets', array( $this, 'render_options' ) );
		$this->register_all_setings();
	}

	function render_options() {
		require_once(dirname(__FILE__).'/sbf_admin.php');
	}

	function add_settings_link( $links ) {
		$img = '<img style="vertical-align: middle;width:24px;height:24px;border:0;" src="'. plugin_dir_url( __FILE__ ) . 'sbf_lib/bitcoin_64.png'.'"></img>';	
		$settings_link = '<a href="' . admin_url('/options-general.php?page=simple-bitcoin-faucets') . '">' . $img . __( 'Settings' ) . '</a>';
		array_unshift($links , $settings_link);	
		return $links;
	}	
}
$GLOBALS['simple_bitcoin_faucets_plugin'] = new Simple_Bitcoin_Faucets_Plugin;


