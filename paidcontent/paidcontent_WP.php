<?php
//just for readability, to not to overwhelm main plugin file
if( !session_id() )
{
    session_start();
}
//print_r($_SESSION);

function SBF_WP_get_paidcontent_init()
{
//	SBF_WP_paidcontent_settings_to_session();
	if(isset($_GET['CRYPTOOMEPAYFORCONTENT']))
	{
		return SBF_WP_paidcontent_do_deposit($_GET['CRYPTOOMEPAYFORCONTENT'],$_GET['SATOSHI_AMOUNT']);
	}
}




function SBF_WP_paidcontent_do_deposit($content_id,$amount)
{
	ob_clean();
	
	
	$api_key = get_option('sfbg_paidcontent_api_key','');
	
	if(strlen($api_key) < 40)
	{
		die('Please provide correct  cryptoo.me API Key');
	}
	global $SBF_C_session_prefix;
	
	if(!isset($_SESSION[$SBF_C_session_prefix . $content_id  .  '_satoshi_amount']))
	{
		print_r($_SESSION);	
		die("Unknown CONTENT_ID ($content_id)"); 
	}
	
	$satoshi_amount = $_SESSION[$SBF_C_session_prefix . $content_id  .  '_satoshi_amount']; 
	if($satoshi_amount == 'allow_edit')
	{
		$satoshi_amount = $amount;
	}
	$satoshi_amount = intval($satoshi_amount);
	
	if(!isset($_GET['data']))
	{
		SBF_WP_paidcontent_step_one($content_id, $satoshi_amount);//start transaction
	}
	else //no amount - we check
	{
		SBF_WP_paidcontent_step_two();//check transaction
	}
	die;
}

function SBF_WP_paidcontent_step_two()
{
//print_r($_GET); die();
	global $SBF_C_session_prefix;
	$api_key = get_option('sfbg_paidcontent_api_key','');
	
	$content_id = $_GET['CRYPTOOMEPAYFORCONTENT'];
	
	if(	( intval($_SESSION["cm_payforcontent_amount"]) > 0) && ($_SESSION["cm_payforcontent_invid"]) )
	{
		$fields = array(
			'key'=> $api_key,
			'invid'=>$_SESSION["cm_payforcontent_invid"]
		);

		$out = wp_remote_post( 'https://cryptoo.me/api/v1/invoice/state/', array(
			'method' => 'POST', 
			'body' => $fields)  );
		$out_ison = json_decode($out['body']);

		if($out_ison->success == true)
		{
			if($_SESSION["cm_payforcontent_amount"] == $out_ison->invoice->amount)
			{
				$pay_time = time();
				$_SESSION[$SBF_C_session_prefix . $content_id  .  '_paid'] = $pay_time;
				$current_user_id = get_current_user_id();
				if($current_user_id != 0) //logged
				{
					update_user_meta( $current_user_id, $SBF_C_session_prefix . $content_id  .  '_paid', $pay_time);
				}				
				$ret = 'Thank you!';
			}
			else
			{
				$ret = $out_ison->message . '<br>Amount error. Try again';
			}
		}
		else
		{
			$ret = 'Transaction error. Try again';
		}
		unset($_SESSION["cm_payforcontent_amount"]);
		unset($_SESSION["cm_payforcontent_invid"]);	
	}
	else //session check
	{
		$ret = 'Unexpected data. Try again';
	}
	$js = "<script>window.opener.focus();window.opener.location.reload();window.close();</script>";
	die("$js<center>$ret<br><button onclick='window.close()'>close</button></center>");
}//step_two

function  SBF_WP_paidcontent_step_one($content_id, $amount)
{
	$api_key = get_option('sfbg_paidcontent_api_key','');
	

	$server_request_scheme = 'http';
	if( (! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
		(! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
		(! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ) {
		$server_request_scheme = 'https';
	}
	
	$self_url = $server_request_scheme . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

	$callback_url = $self_url . '';

	
	$fields = array(
		'key'=>$api_key,
		'amount'=>$amount,
		'notice'=>"Pay for Content ($content_id) ",
		'data'=>'CONTENT:'. $content_id . ',AMOUNT:' . $amount,
		'redirect_url'=>$callback_url,
	);

	$out = wp_remote_post( 'https://cryptoo.me/api/v1/invoice/create/', array(
		'method' => 'POST', 
		'body' => $fields)  );
	$out_ison = json_decode($out['body']);	
//print_r($out_ison); die();
	$_SESSION["cm_payforcontent_amount"] = $amount;
	$_SESSION["cm_payforcontent_invid"] = $out_ison->invid;
 
	header('Location: https://cryptoo.me/api/v1/invoice/open/'.$out_ison->invid);
}//step_one

