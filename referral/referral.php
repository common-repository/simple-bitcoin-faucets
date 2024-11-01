<?php 
/* referral.php */
if( !session_id() )
{
    session_start();
}
//SBF_CM_referral_unset(); die('.-.'); //uncomment to reset
//print_r($_SESSION); die('1');  


function SBF_CM_referral_unset() //for debugging purposes
{
	unset($_SESSION['BCNREF_DEBUG']);
	unset($_SESSION['BCNREF_VISITED_PAID']);
	unset($_SESSION['BCNREF_ADDR']);
	unset($_SESSION['BCNREF_VISITED']);
	unset($_SESSION['BCNREF_PAID_MSG']);
	unset($_SESSION['BCNREF_PAID_MSG']);	
	unset($_SESSION['BCNREF_VISITED_COUNT']);
	set_transient( 'SBF_CM_REFERRAL_PAID_IPS', '', 1 );
	set_transient( 'SBF_CM_REFERRAL_LOG', '', 1 );
}

//not in use, register/login in simple-bitcoin-facets.php
function SBF_CM_referral_register_process($r_apikey,$r_bonus)
{
/*
	if(SBF_CM_referral_is_valid_address($_SESSION['BCNREF_ADDR'])) //presume valid , 26 - 35
	{
		SBF_CM_referral_log($r_bonus,'R');
		$_SESSION['BCNREF_PAID_MSG'] = SBF_CM_referral_send($r_apikey,$_SESSION['BCNREF_ADDR'],$r_bonus); //silently
		$_SESSION['BCNREF_REGISTRATION_PAID'] = $r_bonus;	
		$_SESSION['BCNREF_VISITED_PAID'] = 0; //NOT double-pay
	}
*/
}

function SBF_CM_referral_getRealUserIp(){
    switch(true){
      case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
      case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
      case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
      default : return $_SERVER['REMOTE_ADDR'];
    }
}

function SBF_CM_get_referral_log_rows($show_type = 1)
{
	$ret = '';
	$log_array = get_transient( 'SBF_CM_REFERRAL_LOG' );
	if(!is_array($log_array))
	{
		return('');
	}	
	for($i=0; $i<count($log_array); $i++)
	{
		$item = $log_array[$i];
		$c = '';
		$c .= '<b>time:</b> ' . date('Y-m-d H:i:s', $item['time_start']) . ' -  ' . date('H:i:s',$item['time_end']) . ' ('.  ($item['time_end'] - $item['time_start']) . ' s)<br>';
		$c .= '<b>Referring URL:</b> <a target=_blank href="'.$item['referral_url'].'">' . urldecode($item['referral_url']) . '</a><br>';
		$c .= '<b>Referrer:</b> ' . $item['referrer_address'] . '<br>';
		$c .= '<b>Paid:</b> ' . $item['amount'] . ' satoshi<br>';
		$c .= '<b>Visitor IP:</b> ' . $item['remote_ip'] . '<br>';
		$c .= '<b>Visited URLs:</b><br>' . str_replace(' ', '<br>&nbsp;&nbsp;&nbsp;', urldecode(trim($item['visited_urls']) )). '<br>';
		if(SBF_CM_referral_is_valid_address($item['referrer_address']))//or it was not paid - no reason for logging
		{
			$ret .= "<tr><td>$i</td><td>".$c."</td></tr>\n";
		}
	}
	return($ret);
}

function SBF_CM_referral_log($amount,$pay_type)
{
	$new_record = array();
	$new_record['time_start'] = $_SESSION['BCNREF_TIME_START'];		
	$new_record['time_end'] = $_SESSION['BCNREF_TIME_END'];
	$new_record['amount'] = $amount;
	$new_record['pay_type'] = $pay_type;
	$new_record['remote_ip'] = SBF_CM_referral_getRealUserIp();	
	$new_record['visited_urls'] = $_SESSION['BCNREF_VISITED'];
	$new_record['referrer_address'] = $_SESSION['BCNREF_ADDR'];
	$new_record['referral_url'] = $_SESSION['BCNREF_REF'];
	$log_array = get_transient( 'SBF_CM_REFERRAL_LOG' );
	if(!is_array($log_array))
	{
		$log_array = array();
	}
	array_unshift($log_array,$new_record); //insert first
	$log_array = array_slice($log_array, 0, 100, TRUE );
	set_transient( 'SBF_CM_REFERRAL_LOG', $log_array, 30 * DAY_IN_SECONDS );
}

function SBF_CM_referral_visit_process($bcnref,$v_apikey,$v_bonus,$v_pages,$v_interval,$v_forbidden_a,$v_forbidden_ip)
{
//SBF_CM_referral_unset(); return; //uncomment to reset
//$_SESSION['BCNREF_DEBUG'] = array($bcnref,$v_apikey,$v_bonus,$v_pages);
	
	if( ($v_bonus <= 0) || (strlen(trim($v_apikey)) < 40) || (defined( 'DOING_AJAX' )) )
	{
		return;
	}

	if($_SESSION['BCNREF_VISITED_PAID'] != 'YES')//we do nothing if already paid in this session
	{
		if( SBF_CM_referral_is_valid_address($bcnref) //good bitcoin address
			&& ($_SESSION['BCNREF_ADDR'] != $bcnref)  //new one - not set yet
			&& (strlen(trim($_SERVER['HTTP_REFERER'])) > 0) //has referrer
			&& (strpos($v_forbidden_a,$bcnref) === FALSE ) //not forbidden
			) //got new referral
		{
			$_SESSION['BCNREF_ADDR'] = $bcnref;
			$_SESSION['BCNREF_REF'] = $_SERVER['HTTP_REFERER'] ; //. '|' . strlen(trim($_SERVER['HTTP_REFERER'])) ;
			$_SESSION['BCNREF_VISITED'] = '' ; //do over
			$_SESSION['BCNREF_TIME_START'] = time(); //$_SERVER['REQUEST_TIME'];
		}

		if( (strlen($_SESSION['BCNREF_ADDR']) > 0) )//we do have referrer to process and configured
		{
			$_SESSION['BCNREF_VISITED'] = str_replace($_SERVER['REQUEST_URI'] . ' ','',$_SESSION['BCNREF_VISITED']);//remove if was there
			$_SESSION['BCNREF_VISITED'] .= $_SERVER['REQUEST_URI'] . ' ' ; //add current URI and space
			$_SESSION['BCNREF_VISITED_COUNT'] = substr_count($_SESSION['BCNREF_VISITED'], ' ');
			if($_SESSION['BCNREF_VISITED_COUNT'] == $v_pages) // equal ! we pay once even if move visited 
			{
				$paid_IPs = get_transient( 'SBF_CM_REFERRAL_PAID_IPS' );
				$current_ip = SBF_CM_referral_getRealUserIp();
				if( (strpos($paid_IPs,$current_ip) === FALSE)  //the dude did not visit us before in reasonable time
					&& (strpos($v_forbidden_ip,$current_ip) === FALSE) //not known bad guy
					&& (strpos($v_forbidden_a,$_SESSION['BCNREF_ADDR']) === FALSE ) //not forbidden after count started
					&& ( ( time() - $_SESSION['BCNREF_TIME_START'] ) >= $v_interval) //long enough on site
					) 
				{
					$_SESSION['BCNREF_TIME_END'] = time(); //$_SERVER['REQUEST_TIME'];
					SBF_CM_referral_log($v_bonus,'V');
					$_SESSION['BCNREF_PAID_MSG'] = SBF_CM_referral_send($v_apikey,$_SESSION['BCNREF_ADDR'],$v_bonus); //silently
					$_SESSION['BCNREF_VISITED_PAID'] = 'YES'; //no more during this session
					$_SESSION['BCNREF_VISITED'] = '' ; //do over	
					$paid_IPs = substr($paid_IPs, -10000); //cut to last 1000 chars
					$paid_IPs = $paid_IPs . ' ' . $current_ip;
					set_transient( 'SBF_CM_REFERRAL_PAID_IPS', $paid_IPs, 7 * DAY_IN_SECONDS );
				}
			}
		}
	}
//	if(isset($_GET['BTCREF']))
}

function SBF_CM_referral_is_valid_address($address)
{
	if( (strlen($address) > 25) && (strlen($address) < 36) )
	{
		return TRUE;
	}
	return FALSE;
}


function SBF_CM_referral_send($api_key,$address,$amount)
{
	if( ($amount <= 0) || (strlen($api_key) != 40) || (!SBF_CM_referral_is_valid_address($address)) )
	{
		return; //silently
	}
	
	$fields = array(
		'api_key'=> $api_key,
		'to'=>$address,
		'amount'=>$amount
	);


	$out = wp_remote_post( 'https://cryptoo.me/api/v1/send', array(
		'method' => 'POST', 
		'body' => $fields)  );
	$out_ison = json_decode($out['body']);	
	
    if(!isset($out['body']))
    {
          return; //silently
    }	
	
	if($out_ison->status == 200) //we ok
	{
		return ''; //if we ok we return empty string - no error message
	}
//if we here - it was not 200, error happened
	$msg = $out_ison->message;//$out_ison->message;
	return $msg;
}//SBF_CM_referral_send

function SBF_CM_referral_create_link_constructor()
{
	$ret = '';
	$placeholder = __('your Bitcoin Address','simple-bitcoin-faucets');
	$ret .= "\n<div 	class='sbfr_link_constructor_wrapper'>";
	$ret .= "\n<div 	class='sbfr_link_constructor_res' style='cursor:pointer;display:inline-block; border-bottom:1px dashed blue;'>".get_site_url()."?BTCREF="."</div>";
	$ret .= "\n<div 	class='sbfr_link_constructor_edit' style='display:none;'>";
	$ret .= "\n<div 	class='sbfr_link_constructor_edit_base' style='display:inline;'>".get_site_url()."?BTCREF="."</div>";
	$ret .= "\n<input class='sbfr_link_constructor_edit_addr' type='text' style='width:initial;' placeholder='$placeholder' />";	
	$ret .= "\n</div>"; //sbfr_link_constructor_edit
	$ret .= "\n</div>"; //link_constructor_wrapper
	
	$ret .= "\n<style>


			</style>";	
			
	$ret .= "\n<script>
var sbfr_link_constructor_switch = function(to_edit){
	if(to_edit){
		jQuery('.sbfr_link_constructor_res').hide();
		jQuery('.sbfr_link_constructor_edit').show('fast',function(){jQuery('.sbfr_link_constructor_edit_addr').focus().select();});	
	}else{
		var e = jQuery('.sbfr_link_constructor_edit_addr').val();
		var b = jQuery('.sbfr_link_constructor_edit_base').html();	
		localStorage.setItem('BTCREF', e );
		document.cookie = 'BTCREF='+e+'; expires=Fri, 3 Aug 2051 20:47:11 UTC; path=/';
		jQuery('.sbfr_link_constructor_res').html(b + e).show();
		jQuery('.sbfr_link_constructor_edit').hide();
	}
};
jQuery(document).ready(function () {	
	var current_btcref = localStorage.getItem('BTCREF');
	if(current_btcref != null){
		jQuery('.sbfr_link_constructor_edit_addr').val(current_btcref);
		jQuery('.sbfr_link_constructor_res').html(jQuery('.sbfr_link_constructor_res').html() + current_btcref);
		
	}else{
		sbfr_link_constructor_switch(true);
	}
	jQuery('.sbfr_link_constructor_edit_addr').on('change paste focusout keyup', function () {
		jQuery(this).val(jQuery(this).val().trim()); 
//console.log('|',jQuery(this).val(),'|');
		if( !jQuery(this).val().match(/^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$/) ){
			jQuery('.sbfr_link_constructor_edit_addr').css('background-color','red');
		}else{
			jQuery('.sbfr_link_constructor_edit_addr').css('background-color','initial');	
			sbfr_link_constructor_switch(false);
		}
	});
	jQuery('.sbfr_link_constructor_res').on('click', function () {
		sbfr_link_constructor_switch(true);
	});	
	
});
			</script>";	
	return($ret);
}//create_referral_link_constructor














?>