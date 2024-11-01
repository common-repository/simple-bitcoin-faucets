<?php
?>
<script>
<?php echo($this->main_js_shortcode_localize()); ?>
</script>
<?php echo($this->referral_shortcode_localize()); ?> 
<?php echo($this->referral_shortcode_top()) ?> 

<hr>
<?php _e( 'This functionality mimics <a href="https://cryptoo.me/referral-program/">Cryptoo.me referral program</a>', 'simple-bitcoin-faucets' ); ?>.
<br>
<?php _e( 'Anybody can create referral link with the constructor and own Bitcoin address, than place the link somewhere in the Net.', 'simple-bitcoin-faucets' ); ?> 
<br>
<?php _e( 'When someone following the link registers, or visits several pages, satoshi bonus will be deposited to the Bitcoin address of the link owner.', 'simple-bitcoin-faucets' ); ?>
<br><a href="javascript:document.getElementById('referral_hints').scrollIntoView();"><b><?php _e( 'Scroll to Hints', 'simple-bitcoin-faucets' ); ?></b></a>


<hr>
		
<div id="sfbg_referral_settings" style="float: left; padding:10px;">	
	<?php submit_button(); ?>
	<div class="vp_trof_must_save"><?php _e( 'Please save', 'simple-bitcoin-faucets' ); ?></div>
	
	
	<?php _e( 'Cryptoo.me <b>API Key</b> (registration)', 'simple-bitcoin-faucets' ); ?>:
	<input type="text" id='sfbg_referral_register_api_key' name='sfbg_referral_register_api_key' maxlength="40" 
	value='<?php echo esc_attr( get_option('sfbg_referral_register_api_key','') ); ?>' >
	</input> 
	<div class='referral_comments'>
		<?php _e( 'Get the API Key for free at', 'simple-bitcoin-faucets' ); ?> 
		<a target=_blank href='<?php _e( 'https://cryptoo.me/applications/', 'simple-bitcoin-faucets' ); ?>'  >cryptoo.me</a>.
	</div>
	
	<hr>

	<?php _e( 'Pay for active registration', 'simple-bitcoin-faucets' ); ?>:
	<input type="text" min="1" max="10000" size=3 class='trof_num' id='sfbg_referral_registration_bonus' name='sfbg_referral_registration_bonus' maxlength="10" 
	value='<?php echo esc_attr( get_option('sfbg_referral_registration_bonus','50') ); ?>' >
	</input> 
	<?php _e( 'satoshi', 'simple-bitcoin-faucets' ); ?>
	<div class='referral_comments'>
		<?php _e( 'Only active (confirmed) registrations are paid for', 'simple-bitcoin-faucets' ); ?>.
	</div>

	
	<hr><hr>
	<?php _e( 'Cryptoo.me <b>API Key</b> (visits)', 'simple-bitcoin-faucets' ); ?>:
	<input type="text" id='sfbg_referral_visits_api_key' name='sfbg_referral_visits_api_key' maxlength="40" 
	value='<?php echo esc_attr( get_option('sfbg_referral_visits_api_key','') ); ?>' >
	</input> 
	<div class='referral_comments'>
		<a href="javascript:document.getElementById('referral_hints').scrollIntoView();"><?php _e( 'See Hints', 'simple-bitcoin-faucets' ); ?></a>
	</div>
	
	
	<?php _e( 'Pay', 'simple-bitcoin-faucets' ); ?>
	<input type="text" min="0" max="100" size=2 class='trof_num' id='sfbg_referral_visit_bonus' name='sfbg_referral_visit_bonus' maxlength="10" 
	value='<?php echo esc_attr( get_option('sfbg_referral_visit_bonus','10') ); ?>' >
	</input> 
	<?php _e( 'satoshi', 'simple-bitcoin-faucets' ); ?>
	<?php _e( 'if at least', 'simple-bitcoin-faucets' ); ?>
	<input type="text" min="1" max="100" size=2 class='trof_num' id='sfbg_referral_visit_pages' name='sfbg_referral_visit_pages' maxlength="10" 
	value='<?php echo esc_attr( get_option('sfbg_referral_visit_pages','5') ); ?>' >
	</input> 
	<?php _e( 'pages were visited', 'simple-bitcoin-faucets' ); ?>
	<br>
	<?php _e( 'Minimal total visiting time', 'simple-bitcoin-faucets' ); ?>
	<input type="text" min="1" max="100" size=2 class='trof_num' id='sfbg_referral_visit_time' name='sfbg_referral_visit_time' maxlength="10" 
	value='<?php echo esc_attr( get_option('sfbg_referral_visit_time','30') ); ?>' >
	</input> 	
	<?php _e( 'seconds', 'simple-bitcoin-faucets' ); ?>
	<div class='referral_comments'>
		<?php _e( 'Paid only once per visit', 'simple-bitcoin-faucets' ); ?>.<br>
	</div>
	<hr>
	<?php _e( 'Do not pay to Referral Bitcoin Addresses', 'simple-bitcoin-faucets' ); ?>:
	<br>
	<textarea id='sfbg_referral_forbidden_bitcoin_addresses' name='sfbg_referral_forbidden_bitcoin_addresses' style='width:100%;'><?php echo esc_attr( get_option('sfbg_referral_forbidden_bitcoin_addresses','') ); ?></textarea>
	<div class='referral_comments'>
		<?php _e( 'Add suspicious addresses', 'simple-bitcoin-faucets' ); ?>.	
	</div>
	<hr>
	<?php _e( 'Do not pay for visiting IP Addresses', 'simple-bitcoin-faucets' ); ?>:
	<br>
	<textarea id='sfbg_referral_forbidden_ip_addresses' name='sfbg_referral_forbidden_ip_addresses' style='width:100%;'><?php echo esc_attr( get_option('sfbg_referral_forbidden_ip_addresses','') ); ?></textarea>
	<div class='referral_comments'>	
		<?php _e( 'For example from <a target=_blank href="https://myip.ms/browse/blacklist/Blacklist_IP_Blacklist_IP_Addresses_Live_Database_Real-time">here</a>', 'simple-bitcoin-faucets' ); ?>.	
	</div>
	<hr><hr>	

	<?php submit_button(); ?>
</div>


<div id="sfbg_referral_example" style="float: left; padding:10px; max-width:50%; padding-left:10px;">
<script>
</script>



<?php _e( 'In the Referral Program Page use shortcodes', 'simple-bitcoin-faucets' ); ?>:
<br><br>
<code>[SBFG_REF_LINK_CONSTRUCTOR]</code>  <?php _e( 'shows referral link constructor', 'simple-bitcoin-faucets' ); ?>.
<br><br>
<code>[SBFG_REF_SINGUP_BONUS]</code>  <?php _e( 'shows registration bonus satoshi value', 'simple-bitcoin-faucets' ); ?>.
<br><br>
<code>[SBFG_REF_VISITS_BONUS]</code>  <?php _e( 'shows pageviews bonus satoshi value', 'simple-bitcoin-faucets' ); ?>.
<br><br>
<code>[SBFG_REF_VISITS_PAGES]</code>  <?php _e( 'shows number of pages to visit', 'simple-bitcoin-faucets' ); ?>.
<br><hr>
<a href="#" onclick="window.open(top.location.href+'&fid=0&t=R_<?php _e( 'EN', 'simple-bitcoin-faucets' ); ?>&title=<?php _e( 'Referral%20Program', 'simple-bitcoin-faucets' ); ?>');return false;">
	<?php _e('Generate Referral Program Page', 'simple-bitcoin-faucets' ); ?>
</a>, <?php _e('edit, save, add to menu - you all set! ', 'simple-bitcoin-faucets' ); ?>
<!--
<hr>
<?php _e( 'For example, this code', 'simple-bitcoin-faucets' ); ?>:
<br>
<code style='width:100%;'>
Send visitors via this link:
[SBFG_REF_LINK_CONSTRUCTOR]
For every confirmed registration wi instantly will send you [SBFG_REF_SINGUP_BONUS] satoshi.
If visitor is not going to register, but just views [SBFG_REF_VISITS_PAGES] pages, 
you still getting [SBFG_REF_VISITS_BONUS] satoshi as consolation prize !
</code>
<br>
<br>
<?php _e( 'Will render', 'simple-bitcoin-faucets' ); ?>:
-->


</div>


<table width='95%' align='center' border=1 class='table table-striped table-condensed dataTable no-footer'>
<!--
<th><td><?php _e( 'Log', 'simple-bitcoin-faucets' ); ?></td></th>
-->
<?php 
	$rows = SBF_CM_get_referral_log_rows(); 
	if($rows == '')
	{
		echo("<tr><td>".__( 'log is empty yet', 'simple-bitcoin-faucets' )."</td></tr>");
	}
	else
	{
		echo($rows);
	}
?>
</table>



<div id='referral_hints' style="clear:both;">
<hr>
<b><?php _e( 'Hints', 'simple-bitcoin-faucets' ); ?>:</b><br>
&nbsp;-&nbsp;
<?php _e('Same API Key can be used for registrations and page visits. However, using seperate Keys will simplify the track of performance, and increase the exposure of your website in the <a href="https://cryptoo.me/rotator/">App List</a> ', 'simple-bitcoin-faucets' ); ?>.
 <?php _e('History of the payments is available in the <a href="https://cryptoo.me/applications/">Application Manager</a> under "Payouts" link of your Application', 'simple-bitcoin-faucets' ); ?>.
<hr>&nbsp;-&nbsp;
<?php _e('Make sure you have some kind of captcha plugin to prevent bot registrations', 'simple-bitcoin-faucets'); ?>.
<hr>

</div>

 
<script>

function referral_tab_activated()
{
//do nothing for now
}

function referral_check_api_key(selector)
{
	var o = jQuery(selector);
	var b_pref = '0';
	if(o.val().length < 40)
	{
		b_pref = '1';
	}
	o.css('border',b_pref + 'px solid red');
}

jQuery(document).ready(function () {
	referral_check_api_key('#sfbg_referral_register_api_key');
	referral_check_api_key('#sfbg_referral_visits_api_key');

	jQuery("#sfbg_referral_register_api_key").on('change keyup paste', function () {
		referral_check_api_key('#sfbg_referral_register_api_key');
	});
	
	jQuery("#sfbg_referral_visits_api_key").on('change keyup paste', function () {
		referral_check_api_key('#sfbg_referral_visits_api_key');
	});	
	
	
	jQuery(".trof_num").on('change keyup paste', function () {
		var s = jQuery(this).val();
		var n = s.replace(/[^0-9]/g,'');
		jQuery(this).val(n);	
		if(n.length == 0)
		{
			jQuery(this).val('0');
		}
		jQuery(this).val(parseInt(jQuery(this).val()));	
	});
	

	jQuery("#sfbg_referral_settings input , #sfbg_referral_settings textarea ").on('change', function () {
		jQuery(".vp_trof_must_save").show();
	});

});

</script>		
<?php

?>