<?php 
//paid content stuff
//just for readability, to not to overwhelm main plugin file
if( !session_id() )
{
    session_start();
}

$SBF_C_static_out = FALSE; //we we print out sj end such only once
$SBF_C_session_prefix = 'SBF_PC1_';
//returns TRUE if paid, FALSE if not
function SBF_C_ispaid($content_id,$label)
{
//return TRUE;
	global $SBF_C_session_prefix;
	
	$current_user_id = get_current_user_id();
	if($current_user_id != 0) //logged
	{
		$paid = get_user_meta($current_user_id,$SBF_C_session_prefix . $content_id  .  '_paid',true );
 //print_r("\n<br>|$content_id :$paid|" . $_SESSION[$SBF_C_session_prefix . $content_id  .  '_paid']); 
		if(($paid != ''))
		{
			$_SESSION[$SBF_C_session_prefix . $content_id  .  '_paid'] = $paid;
		}
	}
//print_r("$paid $valid_seconds");
//print_r($_SESSION);  die;	

	if( (isset($_SESSION[$SBF_C_session_prefix . $content_id  .  '_paid']) ) && (isset($_SESSION[$SBF_C_session_prefix . $content_id  .  '_valid_seconds']) ))
	{
		$paid = intval($_SESSION[$SBF_C_session_prefix . $content_id  .  '_paid']);
		$valid_seconds = intval($_SESSION[$SBF_C_session_prefix . $content_id  .  '_valid_seconds']);
	
		if( $paid + $valid_seconds > time() )
		{
			return TRUE;
		}
	}
	delete_user_meta( $current_user_id, $SBF_C_session_prefix . $content_id  .  '_paid');
	return FALSE; //if we here
}//EOF SBF_C_ispaid

function SBF_C_filter_id($id)
{
	$ret = preg_replace("/\W|_/", '', $id);
	return $ret;
}

function SBF_C_get_statuc_out()
{
	global $SBF_C_static_out;
	if($SBF_C_static_out == TRUE)
	{
		return '';
	}
	$SBF_C_static_out = TRUE;
	$static_out = '';
	$static_out .= "\n<script>";
	$static_out .= "\n var paidcontent_text_enter_amount = '". __( 'Satoshi amount', 'simple-bitcoin-faucets' ) ."'; ";	
	$static_out .= "\n var paidcontent_text_cancel = '". __( 'Cancel', 'simple-bitcoin-faucets' ) ."'; ";		
	$static_out .= "\n</script>";	
	$static_out .= "\n<link href='" . plugin_dir_url( __FILE__ ) . "../sbf_lib/messagebox.css' rel='stylesheet'>";
	$static_out .= "\n<script src='" . plugin_dir_url( __FILE__ ) . "../sbf_lib/messagebox.js'></script>";	
	$static_out .=  "\n<script src='" . plugin_dir_url( __FILE__ ) . "paidcontent.js'></script>";
	return $static_out;
}

//shortcode SBF_CONTENT_PAY_PROMPT SATOSHI_AMOUNT VALID_SECONDS ALLOW_EDIT
function SBF_C_pay_prompt($atts = [], $content = null, $tag = '')
{
//return "SBF_C_pay_prompt|" . print_r($atts,true) . "|" . print_r($content,true) . "|" . print_r($tag,true);
	global $SBF_C_session_prefix;
	$atts = array_change_key_case((array)$atts, CASE_LOWER);
	if(!isset($atts['content_id']))
	{
		return " SBF_CONTENT_PAY_PROMPT ERROR: NO CONTENT_ID ! ";
	}
	$content_id = SBF_C_filter_id($atts['content_id']);
	if(SBF_C_ispaid($content_id,1)) //paid - show block from inside
	{
		return('');
	}
	else //bit more complex - gotta show pay link
	{
		$satoshi_amount = 1; //satoshi
		if(isset($atts['satoshi_amount'])) 
		{
			$satoshi_amount = intval($atts['satoshi_amount']);
			if($satoshi_amount < 1)
				$satoshi_amount = 1;
		}
		$_SESSION[$SBF_C_session_prefix . $content_id  .  '_satoshi_amount'] = $satoshi_amount;
		
		$valid_seconds = 60 * 60 * 24 * 365; //year
		if(isset($atts['valid_seconds'])) 
		{
			$valid_seconds = intval($atts['valid_seconds']);
			if($valid_seconds < 0 )
				$valid_seconds = 0;
		}
		$_SESSION[$SBF_C_session_prefix . $content_id .  '_valid_seconds'] = $valid_seconds;
		
		$allow_edit = FALSE;
		if(isset($atts['allow_edit'])) 
		{
			$val = trim(strtolower($atts['allow_edit']));
			if( ($val == 'yes') || ($val == 'true') || ($val == '1'))
			{
				$allow_edit = TRUE;
				$_SESSION[$SBF_C_session_prefix . $content_id  .  '_satoshi_amount'] = 'allow_edit';
			}
		}
		
		$ret =  '';
		$ret .= SBF_C_get_statuc_out();		//before we mention sbf_paidcontent_deposit()
		$link_start = "<a href='javascript:void(0)' onClick='sbf_paidcontent_deposit(\"$content_id\",$satoshi_amount,$valid_seconds,".((bool)$allow_edit ? 'true' : 'false').");' class='SBF_PAIDCONTENT_PAY_LINK' >"; 
		$ret .=  $link_start . $content . '</a>';
		return($ret);
	}	
}

//shortcode SBF_CONTENT_UNPAID
function SBF_C_unpaid($atts = [], $content = null, $tag = '')
{
//return "SBF_C_unpaid|" . print_r($atts,true) . "|" . print_r($content,true) . "|" . print_r($tag,true);	
	$atts = array_change_key_case((array)$atts, CASE_LOWER);
	if(!isset($atts['content_id']))
	{
		return " SBF_CONTENT_UNPAID ERROR: NO CONTENT_ID ! " . print_r($atts,true); 
	}
	$content_id = SBF_C_filter_id($atts['content_id']);
	if(SBF_C_ispaid($content_id,2)) //paid - show nothing
	{
		return('');
	}
	else //unpaid - show block from inside
	{
		return($content);
	}
}//EOF SBF_C_unpaid


//shortcode SBF_CONTENT_PAID
function SBF_C_paid($atts = [], $content = null, $tag = '')
{
//return "SBF_C_paid|" . print_r($atts,true) . "|" . print_r($content,true) . "|" . print_r($tag,true);
	$atts = array_change_key_case((array)$atts, CASE_LOWER);
	if(!isset($atts['content_id']))
	{
		return " SBF_CONTENT_PAID ERROR: NO CONTENT_ID ! ";
	}
	$content_id = SBF_C_filter_id($atts['content_id']);
	if(SBF_C_ispaid($content_id,3)) //paid - show block from inside
	{
		return($content);
	}
	else //unpaid - show none
	{
		return('');
	}	
}//EOF SBF_C_paid
