<?php 
//SBF_DB_ stuff
//show bonds_box - user,admin
//fetch bonds - admin
//add bond  - admin
//delete bond - admin
//redeem  bond code - user,admin

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	die('no');
}

//experimental stuff, will be included when ready 
include_once( ABSPATH . 'wp-content/plugins/bonds_extra/bonds_extra.php'); //not necessery, require_once causes error

add_action('wp_ajax_SBF_DB_code_action', 'SBF_DB_code_redeem_callback'); //admin
add_action('wp_ajax_nopriv_SBF_DB_code_action', 'SBF_DB_code_redeem_callback'); //user

add_action('wp_ajax_SBF_DB_code_manage_action', 'SBF_DB_code_manage_callback'); //admin 
add_action('wp_ajax_nopriv_SBF_DB_code_manage_action', 'SBF_DB_code_manage_callback'); //user
//ajax, params by POST

$bonds_use_db = true;

function SBF_DB_code_redeem_callback() //ajax. returns message
{
	global $wpdb; //sb - Satoshi Bonds
	$db_prefix = $wpdb->prefix;
	$btc_address =  trim($_POST['R_BTC_ADDRESS']) ;
	$code = trim($_POST['R_CODE']);
	$api_key = get_option('sfbg_bonds_api_key','');
	if(function_exists ('SBF_DB_processing_key')){
		$api_key = SBF_DB_processing_key();
	}

	if( (strlen($code) == 0) || (strlen($btc_address) == 0) )
	{
		$ret = __( 'Bond code and recipient address may not be empty', 'simple-bitcoin-faucets' );
		echo($ret);
		wp_die();
	}	

	$code_index = -1;
	$amount = -1;
	
	$get_bond_sql = "SELECT * FROM `".$db_prefix."sb_Bonds` WHERE `bond_code` = '".$code."' AND 1";
	$bond_row = $wpdb->get_row($get_bond_sql, 'ARRAY_A');
	if($bond_row == NULL){ //not found
		$ret = __( 'There is no outstanding bond with the code', 'simple-bitcoin-faucets' ) . ' ' . $code;
		echo($ret);
		wp_die();
	}
	if($bond_row['redeemed'] != '0000-00-00 00:00:00'){ //found, but already redeemed
		$ret = __( 'This Bond has been redeemed already', 'simple-bitcoin-faucets' );
		echo($ret);
		wp_die();
	}	
	
	$get_block_sql = "SELECT * FROM `".$db_prefix."sb_Blocks` WHERE `id` = ".$bond_row['block_id']." AND 1";
	$block_row = $wpdb->get_row($get_block_sql, 'ARRAY_A');
	if($block_row == NULL){ //not found, NOT SUPPOSE TO HAPPEN
		$ret = __( 'Database Block integrity error on ', 'simple-bitcoin-faucets' ) . ' ' . $code;
		echo($ret);
		wp_die();
	}	
/*	
	$get_key_sql = "SELECT * FROM `".$db_prefix."sb_Keys` WHERE `id` = ".$block_row['key_id']." AND 1";
	$key_row = $wpdb->get_row($get_key_sql, 'ARRAY_A');
	if($block_row == NULL){ //not found, NOT SUPPOSE TO HAPPEN
		$ret = __( 'Database Key integrity error on ', 'simple-bitcoin-faucets' ) . ' ' . $code;
		echo($ret);
		wp_die();
	}	
*/	
//wp_die(print_r($key_row,true)." |$get_key_sql| ");		
	

	$fields = array(
		'api_key'=> $api_key, //$key_row['api_key'],
		'to'=>$btc_address,
		'amount'=>$bond_row['bond_value']
	);
	
	//ok, we are doing it because afted DDoS attack on cryptoo.my their firewall sometimes returns empty code
	$resp_code = ''; //just to tickle while()
	$attempts = 3; //we will do it 3 times
	
	do{
		$attempts--;
		$response = wp_remote_post( 'https://cryptoo.me/api/v1/send', array(
			'method' => 'POST', 
			'body' => $fields)  );
		$resp_body = wp_remote_retrieve_body( $response );
		$resp_code = wp_remote_retrieve_response_code( $response );
	}while( ($resp_code == '') && ($attempts > 0 ) );

	if($resp_code != 200)
	{
		if(trim($resp_code) == '') {
			$ret = __( 'Something went wrong, try again!', 'simple-bitcoin-faucets' ) . "<div style='display:none;'>$resp_body</div>";
		} else {
			$ret = __( 'Unknown error!', 'simple-bitcoin-faucets' ) . " ( $resp_code )<div style='display:none;'>$resp_body</div>";
		}
		
		echo($ret);
		wp_die();	
	}
	
//if we here, request succeeded	
	$body = json_decode($resp_body);
	if($body->status == 200) //we ok
	{
		$msg = 'OK'.__( 'Bond', 'simple-bitcoin-faucets' ) . ' ' . __( 'has been redeemed', 'simple-bitcoin-faucets' ) . ',<br>'; 
		$msg .= $bond_row['bond_value'] . ' ' . __( 'satoshi', 'simple-bitcoin-faucets' ) . ' ' . __( 'sent to', 'simple-bitcoin-faucets' ) . ' '; 
		$msg .= '<a target=_blank href="' . __( 'https://cryptoo.me/deposits/', 'simple-bitcoin-faucets' ).'">' . $btc_address . '</a>.';
		$to_ip = SBF_DB_get_client_ip();
		$set_bond_sql = "UPDATE `".$db_prefix."sb_Bonds` SET `redeemed` = NOW(), `redeemed_to` = '".$btc_address."', `redeemed_ip` = '".$to_ip."' WHERE `id` = ".$bond_row['id']." AND 1";
		if($wpdb->query($set_bond_sql) === FALSE){wp_die('Error on Update Bonds table');} //we screwed
		if($block_row['notify_redeem'] == 1){
			SBF_DB_redeem_notify_block_owner($block_row['wp_owner_id'],$code,$bond_row['bond_value'],$block_row['block_name'],$btc_address,$to_ip);
		}
	}
	else
	{
		$msg = $body->message;
	}
//if we here - it was not 200, error happened
	echo($msg);
	
	wp_die();
}

function SBF_DB_redeem_notify_block_owner($wp_owner_id,$bond_code,$bond_value,$block_name,$to_btc_address,$to_ip){
	$user_info = get_userdata($wp_owner_id);
	
	$current_locale = get_locale(); //gotta switch back  after email is sent
	$bond_owner_locale = get_user_locale($wp_owner_id); //gotta send email in language of bond owner
	switch_to_locale($bond_owner_locale);
	
	$user_lang = $user_info->display_name;
	$user_name = $user_info->display_name;
	$user_email = $user_info->user_email;
	$site_title = get_bloginfo('name');
	$site_email = get_bloginfo('admin_email');
	$site_description = get_bloginfo('description');
	$site_url = get_bloginfo('url');
	$email_text = __(" 	Hello %%USERNAME%%<br/>
	Satoshi Bond with code %%CODE%% (%%VALUE%% satoshi, Block '%%BLOCK%%') has been redeemed to %%TO%% (IP: %%IP%%).<br/>
	You've received this email because Block '%%BLOCK%%' is configured to notify the owner on redemptions. <br/>
	Have a nice day!<br/>
	<a href='%%SITEURL%%'>%%SITENAME%%</a> 
	", 'simple-bitcoin-faucets' );
	$email_text = str_replace('%%USERNAME%%',$user_name,$email_text);
	$email_text = str_replace('%%CODE%%',$bond_code,$email_text);
	$email_text = str_replace('%%VALUE%%',$bond_value,$email_text);
	$email_text = str_replace('%%BLOCK%%',$block_name,$email_text);
	$email_text = str_replace('%%TO%%',SBF_DB_obfuscate_email_address($to_btc_address),$email_text);
	$email_text = str_replace('%%IP%%',$to_ip,$email_text);
	$email_text = str_replace('%%SITENAME%%',$site_description,$email_text);
	$email_text = str_replace('%%SITEURL%%',$site_url,$email_text);
	
	
	$email_subject = __("Bond %%CODE%% of %%VALUE%% satoshi has been redeemed", 'simple-bitcoin-faucets' );
	$email_subject = str_replace('%%CODE%%',$bond_code,$email_subject);
	$email_subject = str_replace('%%VALUE%%',$bond_value,$email_subject);
	
	$headers = array(
		'content-type: text/html', //must have
	);
	add_filter( 'wp_mail_from_name', 'SBF_DB_email_replace_name_from');  
	wp_mail( $user_email, $email_subject, $email_text , $headers );
	remove_filter( 'wp_mail_from_name', 'SBF_DB_email_replace_name_from'); 	
	
	switch_to_locale($current_locale); //return to normal
}//SBF_DB_redeem_notify_block_owner()

function SBF_DB_email_replace_name_from($from_name){
	return get_bloginfo('name'); //replace "WordPress" to site name. Headers do not work for some servers
}

function SBF_DB_obfuscate_email_address($address){
	if(strpos($address,'@') !== FALSE){
		$em   = explode("@",$address);
		$name = implode(array_slice($em, 0, count($em)-1), '@');
		$len  = floor(strlen($name)/2);

		$address =  substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em); 
	}
	return($address);
}

function SBF_DB_get_client_ip() {
  if($_SERVER) {
    if($_SERVER['HTTP_X_FORWARDED_FOR'])
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif($_SERVER['HTTP_CLIENT_IP'])
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    else
      $ip = $_SERVER['REMOTE_ADDR'];
  }
  else {
    if(getenv('HTTP_X_FORWARDED_FOR'))
      $ip = getenv('HTTP_X_FORWARDED_FOR');
    elseif(getenv('HTTP_CLIENT_IP'))
      $ip = getenv('HTTP_CLIENT_IP');
    else
      $ip = getenv('REMOTE_ADDR');
  }
  return $ip;
}//SBF_DB_get_client_ip()


function SBF_DB_show_bond_item($item, $date_time_str,$div_extra_style='') // ajax. show, add, delete. returns 'OK', or error message
{
	$c = '';
	$ret = '';
	$c .= "<b>Code:</b> " . $item['code'] . "<br>\n";
	$c .= "<b>Amount:</b> " . $item['amount'] . " satoshi<br>\n";
	if( (isset($item['block'])) && (strlen($item['block']) > 0) ){ //old version?
		$c .= "<b>Block:</b> " . $item['block'] . "<br>\n"; //new
	}else{
		$c .= "<b>Block:</b> " . $item['amount']." / ". strftime( "%d %b %Y" , $item['created']) . "<br>\n"; //old
	}
	$c .= "<b>Created:</b> " . strftime( $date_time_str , $item['created']) . "<br>\n";
	if(isset($item['redeemed']))
	{
		$c .= "<b>Redeemed:</b> ".  strftime( $date_time_str , $item['redeemed']) . "<br>\n";
		$c .= "<b>To:</b> " . $item['receiver'] . " (" . $item['IP'] . ")<br>\n";
	}
	$ret .= "<div id='SBF_DB_BOND_DIV_".$item['code']."'  class='SBF_DB_BOND_DIV' style='border:1px solid black;width:90%;padding:5px;margin:5px;$div_extra_style'>\n";
	$ret .= "<button type='button' onclick='SBF_DB_do_delete_bond(\"".$item['code']."\");'style='float:right;' class='delete_bond' id='delete_bond_".$item['code']."' >". __( 'Delete Bond', 'simple-bitcoin-faucets' ) ."</button>\n";
	$ret .= $c."\n";
	$ret .= "</div>\n";
	return $ret;
}

function SBF_DB_is_site_admin(){
    return in_array('administrator',  wp_get_current_user()->roles);
}

function SBF_DB_add_block($block_name,$block_quontity,$block_amount){
	global $wpdb; //sb - Satoshi Bonds
	$db_prefix = $wpdb->prefix;
	
	$block_name = trim(str_replace(array('\"',"\'"),array('`','`'),$block_name)); //not going to deal with ' and " at all	
	if($block_name == ''){
		$block_name = $block_quontity . 'x' . $block_amount . ' / ' . strftime( '%d %b %Y' , time());
	}	
	$current_user = wp_get_current_user();
	$default_key = 1;
	$sql_block = "INSERT IGNORE INTO `".$db_prefix."sb_Blocks` (`wp_owner_id`,`key_id`,`block_name`) VALUES (".$current_user->ID.",".$default_key.",'".$block_name."')";
	if($wpdb->query($sql_block) === FALSE){wp_die('Error on Add Bond Block table');}		
	
	$ret = '';
	for($i = 0; $i < $block_quontity; $i++){
		$new_bond_code = md5(rand());//go guess..
			
		$new_record = array();
		$new_record['created'] = time();	
		$new_record['code'] = $new_bond_code;		
		$new_record['amount'] = $block_amount;
		$new_record['block'] = $block_name;
			
		$block_id_sql = "(SELECT `id` FROM `".$db_prefix."sb_Blocks` WHERE `block_name` = '".$block_name."' )";
			
		$sql_bond = "INSERT INTO `".$db_prefix."sb_Bonds` (`block_id`,`bond_code`,`bond_value`) VALUES (".$block_id_sql.", '".$new_bond_code."',".$block_amount.")";
		if($wpdb->query($sql_bond) === FALSE){wp_die("Error on Add Bond Bond table |$sql_bond|");}
		$time_str = strftime( $date_time_str , $new_record['created']);
		$ret .= SBF_DB_show_bond_item($new_record, $date_time_str,'display:none;');
	}//for	
	return($ret);
}//SBF_DB_add_block

function SBF_DB_code_manage_callback() // ajax. show, add, delete. returns 'OK', or error message
{
	if( !is_user_logged_in() ){ 
		wp_die();
	}
	
	global $wpdb; //sb - Satoshi Bonds
	$db_prefix = $wpdb->prefix;
	
	$bond_command =  $_POST['B_COMMAND'] ;
	$bond_param =  $_POST['B_PARAM'] ;
	$bond_param2 =  $_POST['B_PARAM2'] ; //for add - block
	$bond_param3 =  $_POST['B_PARAM3'] ; //for add - number of records to create
	
//	$bonds_array = get_transient( 'SBF_DB_BONDS' );
//	$date_format = get_option( 'date_format' );
//	$time_format = get_option( 'time_format' );	
//	$date_time_str = $date_format . ' ' . $time_format;
//some day http://php.net/manual/en/function.strftime.php#96424

	$date_time_str = '%d %b %Y %H:%M:%S' ; //$date_format . ' ' . $time_format;

	if($bond_command == 'ADD')//$bond_param is amount, $bond_param2 is block_name, $bond_param3 is quantity
	{
		$block_amount = $bond_param; //readability
		$block_quontity = $bond_param3;
		$block_name = trim($bond_param2);
		$records = SBF_DB_add_block($block_name,$block_quontity,$block_amount);
		echo($records);
		wp_die();
	} //ADD

	if($bond_command == 'CHANGE_NOTIFY_BOND_BLOCK'){ //$bond_param is block_id, $bond_param2 = new state
		$extra_where = " 1 ";
		$current_user_id = get_current_user_id();  
		if(!SBF_DB_is_site_admin() ) {
			$extra_where = "`wp_owner_id` = " . $current_user_id;
		}
		$block_id_to_update = $bond_param;
		$new_state = $bond_param2;
		$update_block_sql = "UPDATE ".$db_prefix."sb_Blocks SET `notify_redeem` = $new_state WHERE id=".$block_id_to_update." AND " .$extra_where;
		if($wpdb->query($update_block_sql) === FALSE){wp_die('Error on Bond Block Notify change');}	
		echo($bond_param2);	
		wp_die();
	}	
	
	if($bond_command == 'DELETE_BOND') //$bond_param is bond code here
	{
//not going to check auth - if they have bond code - easier to redeem than to delete
		$delete_bond_sql = "DELETE FROM ".$db_prefix."sb_Bonds WHERE bond_code='".$bond_param."' AND  1";
		if($wpdb->query($delete_bond_sql) === FALSE){wp_die('Error on Bond delete');}
//delete empty blocks		
		$delete_empty_blocks_sql = "DELETE FROM ".$db_prefix."sb_Blocks WHERE id NOT IN (SELECT DISTINCT block_id FROM ".$db_prefix."sb_Bonds WHERE 1)";
		if($wpdb->query($delete_empty_blocks_sql) === FALSE){wp_die("Error on Empty Blocks delete");}
		
		echo($bond_param);	
		wp_die();	
	}	//DELETE_BOND	
	
	
	if($bond_command == 'DELETE_BOND_BLOCK') //$bond_param is block_id , $bond_param2 - block num in the page, going to return it
	{
		$current_user_id = get_current_user_id();  
		if(SBF_DB_is_site_admin()) {
			$block_id_to_delete = $bond_param; //admin can delete anything
		}else{
			$auth_id_sql = "SELECT COUNT(`id`) FROM ".$db_prefix."sb_Blocks WHERE `id` = " . $bond_param ." AND `wp_owner_id` = " . $current_user_id . " AND 1";
			$count_id_to_delete = $wpdb->get_var($auth_id_sql);
			if($count_id_to_delete === NULL){wp_die('Error on Delete Block - count');}
			if($count_id_to_delete == 1){
				$block_id_to_delete = $bond_param;
			}else{ //0
				wp_die('Error on Delete Block - auth');
			}
		}
		
		$delete_block_sql = "DELETE FROM ".$db_prefix."sb_Bonds WHERE block_id=".$block_id_to_delete." AND 1";
		if($wpdb->query($delete_block_sql) === FALSE){wp_die('Error on Bond Block delete');}
		
		echo($bond_param2);	
		wp_die();		
	}	//DELETE_BOND_BLOCK

//TODO fetch only unfolded, add 'FETCH_BLOCK_BONDS' and call on unfold	
	if($bond_command == 'FETCH_BONDS') //sorts by block, returns all in JSON
	{
		$extra_where = '1';
		$current_user_id = get_current_user_id();  
		if( (!SBF_DB_is_site_admin() )  || (!SBF_DB_is_admin_request()) ) {
			$extra_where = "blocks.wp_owner_id = " . $current_user_id;
		}
		$fetch_blocks_sql = "
			SELECT 
				blocks.id AS `block_id`,
				blocks.block_name AS `block`,
				bonds.bond_code  AS `code`,
				bonds.created AS `created`,
				bonds.redeemed AS `redeemed`,
				bonds.redeemed_to AS `to`,
				bonds.bond_value AS `amount`,
				bonds.redeemed_ip AS `IP`,
				blocks.id AS `block_id`,
				blocks.wp_owner_id AS `block_owner`,
				blocks.locked  AS `block_locked`,
				blocks.notify_redeem  AS `block_notify_redeem`,
				bonds.id AS `bond_id`
			FROM ".$db_prefix."sb_Bonds AS bonds
			LEFT JOIN ".$db_prefix."sb_Blocks AS blocks ON bonds.block_id=blocks.id			
			WHERE ".$extra_where." 
			ORDER BY `block_id` DESC, `bond_id` DESC
		";
//die(json_encode($fetch_blocks_sql));		
		$res = $wpdb->get_results( $fetch_blocks_sql, 'OBJECT' );
	
		if(!SBF_DB_is_site_admin()){
			for($i = 0; $i < count($res); $i++){
				$res[$i]->to = SBF_DB_obfuscate_email_address($res[$i]->to);
			}
		}else{
			if(SBF_DB_is_admin_request()){
				for($i = 0; $i < count($res); $i++){
					if($current_user_id != $res[$i]->block_owner){
						$res[$i]->block = "(<a href='".add_query_arg( 'user_id', $res[$i]->block_owner, self_admin_url( 'user-edit.php'))."'>" . $res[$i]->block_owner . '</a>)' . $res[$i]->block;
					}
				}			
			}
		}

		echo(json_encode($res)); 
		wp_die();
	}	//FETCH_BONDS
	wp_die();//unknown command?
}

function SBF_DB_render_bonds_box()
{
	global $Simple_Bitcoin_Faucets_self_version;
	static $i = 0; //so we can use several shortcodes in one page. fool-proof 
	$i++;

	$ret .= " 
	<table id='SBF_DB_table_$i' style='width:100%;'>
		<tr>
			<td style='text-align:right;vertical-align: middle;'>".__( 'Bond Code', 'simple-bitcoin-faucets' ).": </td>
			<td style='text-align:left;vertical-align: middle;'><input id='SBF_DB_redeem_code_$i' type='text' style='width:100%;'></input></td>
		</tr>
		<tr>
			<td style='text-align:right;vertical-align: middle;'>".__( 'Recipient Address', 'simple-bitcoin-faucets' ).": </td>
			<td style='text-align:left;vertical-align: middle;'><input id='SBF_DB_redeem_BTC_address_$i' type='text' style='width:100%;' placeholder='".__( 'Bitcoin or email address', 'simple-bitcoin-faucets' )."'></input></td>
		</tr>
		<tr>
			<td colspan=2 style='text-align:center;vertical-align: middle;'>
				<div id='SBF_DB_msg_$i' style='display:none;margin:5px;font-weight:bold;'></div>
				<div  id='SBF_DB_ajax_wait_$i' class='sbfg_global_loader' style='display:none; position: absolute;' ></div>
				<button type='button' class='btn ' id='SBF_DB_use_code_$i' onclick='SBF_DB_do_redeem_$i();return false'>".__( 'Redeem Bond', 'simple-bitcoin-faucets' )."</button>
			</td>
		</tr>	
	</table>
	<script>
		var lsA_$i = localStorage.getItem('BTC_address');
		if(lsA_$i != null){
			jQuery('#SBF_DB_redeem_BTC_address_$i').val(lsA_$i);
		}
		
		jQuery(document).on('change keyup paste', '#SBF_DB_redeem_BTC_address_$i , #SBF_DB_redeem_code_$i', function (event) {
			if(event.handled !== true){ // This will prevent event triggering more then once
				event.handled = true;
			}else{
				return;
			}
			var c = jQuery('#SBF_DB_redeem_code_$i').val();
			var a = jQuery('#SBF_DB_redeem_BTC_address_$i').val();
			if((a.length > 10) && (c.length > 10) ){
				 jQuery('#SBF_DB_use_code_$i').prop('disabled', false);
			}
		})

		SBF_DB_do_redeem_$i = function(){
			jQuery('#SBF_DB_use_code_$i').prop('disabled', true);
			jQuery('#SBF_DB_table_$i').css('cursor', 'progress');
			jQuery('#SBF_DB_msg_$i').slideUp('fast');
			var data = {
				action: 'SBF_DB_code_action',
				R_BTC_ADDRESS: jQuery('#SBF_DB_redeem_BTC_address_$i').val(),
				R_CODE: jQuery('#SBF_DB_redeem_code_$i').val()
			};
			jQuery('#SBF_DB_ajax_wait_$i').show();
			jQuery.post( '".get_site_url()."/wp-admin/admin-ajax.php', data, function(response) {
					jQuery('#SBF_DB_ajax_wait_$i').hide();
					if(response.indexOf('OK') == 0)	{
						response = response.substr(2);
						jQuery('#SBF_DB_redeem_code_$i').val('');
						jQuery('#SBF_DB_use_code_$i').prop('disabled', true);
						localStorage.setItem('BTC_address',jQuery('#SBF_DB_redeem_BTC_address_$i').val());
						if(typeof bonds_update_list === 'function'){
							bonds_update_list();
						}
					}else{
						jQuery('#SBF_DB_use_code_$i').prop('disabled', false);
					}
					jQuery('#SBF_DB_msg_$i').html(response).slideDown('fast');	
					jQuery('#SBF_DB_table_$i').css('cursor', 'default');
			});			
			return false;
		}

		SBF_DB_get_url_vars_$i = function(){
			var vars = {};
			var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
				function(m,key,value) {
					vars[key] = value;
				});
			return vars;
		}
	
		var bondcode_$i = SBF_DB_get_url_vars_$i()['bond'];
		var address_to_$i = SBF_DB_get_url_vars_$i()['to'];
		jQuery('#SBF_DB_redeem_code_$i').val(bondcode_$i);
		if(address_to_$i){
			jQuery('#SBF_DB_redeem_BTC_address_$i').val(address_to_$i);
		}

	</script>	
	";
	//note realpath(dirname(__FILE__)) - to get one dir up, we are in /bonds, not in plugin directory
	if($i == 1){ 
		$ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( realpath(dirname(__FILE__)) ) . "sbf_lib/sbf.css?ver=$Simple_Bitcoin_Faucets_self_version'>";   
		$ret .= "\n <script src='" . plugin_dir_url(realpath(dirname(__FILE__)) ) . "sbf_lib/sbf.js?ver=$Simple_Bitcoin_Faucets_self_version'></script>";  
	}
	
	return $ret;
}

function SBF_DB_prepare_db_tables(){
	$current_bonds_db_ver = '0.02';
	$installed_bonds_db_ver = get_option( "sfbg_sb_bonds_db_version",'0' );
	global $wpdb; //sb - Satoshi Bonds
	$db_prefix = $wpdb->prefix;
	$charset_collate = $wpdb->get_charset_collate();
	
	$bond_create_table_keys = "CREATE TABLE IF NOT EXISTS `".$db_prefix."sb_Keys` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`wp_owner_id` int(11) unsigned NOT NULL,	
	`service` varchar(20) NOT NULL DEFAULT 'cryptoo',	
	`api_key` varchar(100) NOT NULL,
	`api_secret` varchar(100) NOT NULL DEFAULT '',
	`currency` varchar(10) NOT NULL DEFAULT 'BTC',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)";
 	
	$bond_create_table_bonds = "CREATE TABLE IF NOT EXISTS `".$db_prefix."sb_Bonds` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`block_id` int(11) NOT NULL,
	`bond_code` varchar(255) NOT NULL,
	`bond_value` int(20) NOT NULL,
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`redeemed` TIMESTAMP NOT NULL DEFAULT 0,
	`redeemed_to` varchar(100) NOT NULL DEFAULT '',
	`redeemed_ip` varchar(45) NOT NULL DEFAULT '',
	PRIMARY KEY (`bond_code`),
	UNIQUE KEY `bond_code` (`bond_code`),
	UNIQUE KEY `id` (`id`)
)";

	$bond_create_table_blocks = "CREATE TABLE IF NOT EXISTS `".$db_prefix."sb_Blocks` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`wp_owner_id` int(11) unsigned NOT NULL,
	`key_id` int(11) NOT NULL,
	`block_name` varchar(255) NOT NULL,
	`locked` tinyint(1) NOT NULL DEFAULT 1,
	`notify_redeem` tinyint(1) NOT NULL DEFAULT 1,
	UNIQUE KEY `block_name` (`block_name`),
	UNIQUE KEY `id` (`id`)
);"; 

//wp_die($bond_create_tables_query);

	if ($installed_bonds_db_ver != $current_bonds_db_ver ) {
		$wpdb->show_errors();
		if($wpdb->query($bond_create_table_keys) === FALSE){wp_die();}
		if($wpdb->query($bond_create_table_blocks) === FALSE){wp_die();}
		if($wpdb->query($bond_create_table_bonds) === FALSE){wp_die();}
		SBF_DB_import_to_tables(); //here we hav DB tables ready, let's load data	
		update_option( "sfbg_sb_bonds_db_version", $current_bonds_db_ver,true );
	}else{
//we are current
	}
	
}//SBF_DB_prepare_db_tables()

function SBF_DB_import_to_tables(){
	global $wpdb; //sb - Satoshi Bonds
	$db_prefix = $wpdb->prefix;
	
	$current_user = wp_get_current_user();
	
	$bonds_array = get_transient( 'SBF_DB_BONDS' );
	if($bonds_array && (in_array('administrator',  $current_user->roles)) ){ //old bonds and admin
		//the app key
		$current_key = get_option('sfbg_bonds_api_key','');
		$sql_keys = "INSERT INTO `".$db_prefix."sb_Keys` (`wp_owner_id`,`api_key`) VALUES (".$current_user->ID.", '".$current_key."')";
		if($wpdb->query($sql_keys) === FALSE){wp_die('Error on Keys table');}
		$default_key = $wpdb->insert_id; 
		//all bonds
		for($i=0; $i<count($bonds_array); $i++)
		{	
			$item = $bonds_array[$i]; //'code','amount','created','block','redeemed','receiver','IP'
			if((!isset($item['block'])) || (strlen($item['block']) == 0)){
				$block_name = __( 'LEGACY', 'simple-bitcoin-faucets' );
			}else{
				$block_name = trim($item['block']);
			}
			$block_name = str_replace(array('\"',"\'"),array('`','`'),$block_name); //not going to deal with ' and " at all
			$sql_block = "INSERT IGNORE INTO `".$db_prefix."sb_Blocks` (`wp_owner_id`,`key_id`,`block_name`) VALUES (".$current_user->ID.",".$default_key.",'".$block_name."')";
			if($wpdb->query($sql_block) === FALSE){wp_die('Error on Blocks table');}

			$block_id_sql = "(SELECT `id` FROM `".$db_prefix."sb_Blocks` WHERE `block_name` = '".$block_name."' )";
			$created = date ("Y-m-d H:i:s", $item['created']); 
			if(isset($item['redeemed'])){
				$redeemed = date ("Y-m-d H:i:s", $item['redeemed']); 
				$to = $item['receiver'];
				$ip = $item['IP'];
				$sql_bond = "INSERT IGNORE INTO `".$db_prefix."sb_Bonds` (`block_id`,`bond_code`,`bond_value`,`created`,`redeemed`,`redeemed_to`,`redeemed_ip`) VALUES (".$block_id_sql.", '".$item['code']."',".$item['amount'].",'".$created."', '".$redeemed."','".$to."','".$ip."')";	
			}else{ //not redeemed yet
				$sql_bond = "INSERT IGNORE INTO `".$db_prefix."sb_Bonds` (`block_id`,`bond_code`,`bond_value`,`created`) VALUES (".$block_id_sql.", '".$item['code']."',".$item['amount'].",'".$created."')";		
			}
			if($wpdb->query($sql_bond) === FALSE){wp_die('Error on Bonds table');}
				
		}	
	}
	//delete_transient( 'SBF_DB_BONDS' );
}//SBF_DB_import_to_tables()

function SBF_DB_update_user_api_key($current_key){
	global $wpdb; //sb - Satoshi Bonds
	$db_prefix = $wpdb->prefix;
	$current_user = wp_get_current_user();
	$sql_keys = "UPDATE `".$db_prefix."sb_Keys` SET  `api_key` = '".$current_key."' WHERE  `wp_owner_id` = ".$current_user->ID." AND 1";
	if($wpdb->query($sql_keys) === FALSE){wp_die('Error on Update Keys table');}
}//SBF_DB_update_user_api_key()


function SBF_DB_is_admin_request() {
	$current_url = home_url( add_query_arg( null, null ) );
	$admin_url = strtolower( admin_url() );
	$referrer  = strtolower( wp_get_referer() );
	if ( 0 === strpos( $current_url, $admin_url ) ) {
		if ( 0 === strpos( $referrer, $admin_url ) ) {
			return true;
		} else {
			if ( function_exists( 'wp_doing_ajax' ) ) {
				return ! wp_doing_ajax();
			} else {
				return ! ( defined( 'DOING_AJAX' ) && DOING_AJAX );
			}
		}
	} else {
		return false;
	}
}

function SBF_DB_must_be_user() {
	$must_be_user = __('<a href="/wp-login.php?action=register">Register</a> or <a href="/wp-login.php">Login</a> to access this page !', 'simple-bitcoin-faucets');
	return($must_be_user);
}
