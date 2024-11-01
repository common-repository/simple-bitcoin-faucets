<?php
?>
<script>
<?php echo($this->main_js_shortcode_localize()); ?>
</script>
<?php 
//echo($this->referral_shortcode_localize()); 
//echo($this->referral_shortcode_top()) 
?> 

<hr>
<?php _e( 'Satoshi Paid Content lets you to present content only if it is paid for. Also good for donations' ); ?>.
<br>
<?php _e( 'Use Shortcodes', 'simple-bitcoin-faucets' ); ?>  <br>
<code>[SBF_CONTENT_UNPAID ...]</code><i><?php _e( 'visible before payment, hidden after', 'simple-bitcoin-faucets' ); ?></i><code>[/SBF_CONTENT_UNPAID]</code>
,
<code>[SBF_CONTENT_PAID ...]</code><i><?php _e( 'hidden before payment, visible after', 'simple-bitcoin-faucets' ); ?></i><code>[/SBF_CONTENT_PAID]</code> 
<?php _e( 'and', 'simple-bitcoin-faucets' ); ?> 
<code>[SBF_CONTENT_PAY_PROMPT ...]</code><i><?php _e( 'pay link text', 'simple-bitcoin-faucets' ); ?></i><code>[/SBF_CONTENT_PAY_PROMPT]</code>
 <?php _e( 'where you want the Paid Content to appear', 'simple-bitcoin-faucets' ); ?> , <?php _e( 'or', 'simple-bitcoin-faucets' ); ?>
<br>
<a href="#" onclick="window.open(top.location.href+'&fid=0&t=PCD_<?php _e( 'EN', 'simple-bitcoin-faucets' ); ?>&title=<?php _e( 'Donation', 'simple-bitcoin-faucets' ); ?>');return false;">
	<?php _e('Generate Test Donation Page', 'simple-bitcoin-faucets' ); ?>
</a>
<br>
<a href="#" onclick="window.open(top.location.href+'&fid=0&t=PCA_<?php _e( 'EN', 'simple-bitcoin-faucets' ); ?>&title=<?php _e( 'Paid Article', 'simple-bitcoin-faucets' ); ?>');return false;">
	<?php _e('Generate Test Paid Article Page', 'simple-bitcoin-faucets' ); ?>
</a>
<br>
<a href="#" onclick="window.open(top.location.href+'&fid=0&t=PCP_<?php _e( 'EN', 'simple-bitcoin-faucets' ); ?>&title=<?php _e( 'Paid Picture', 'simple-bitcoin-faucets' ); ?>');return false;">
	<?php _e('Generate Test Paid Picture Page', 'simple-bitcoin-faucets' ); ?>
</a>

<br><a href="javascript:document.getElementById('paidcontent_hints').scrollIntoView();"><b><?php _e( 'Scroll to Hints', 'simple-bitcoin-faucets' ); ?></b></a>


<hr>
		
<div id="sfbg_referral_settings" style="float: left; padding:10px;">	
	
	<?php _e( 'Cryptoo.me <b>API Key</b>', 'simple-bitcoin-faucets' ); ?>:
	<input type="text" id='sfbg_paidcontent_api_key' name='sfbg_paidcontent_api_key' maxlength="40" 
	value='<?php echo esc_attr( get_option('sfbg_paidcontent_api_key','') ); ?>' >
	</input> 
	<div class='paidcontent_comments'>
		<?php _e( 'Get the API Key for free at', 'simple-bitcoin-faucets' ); ?> 
		<a target=_blank href='<?php _e( 'https://cryptoo.me/applications/', 'simple-bitcoin-faucets' ); ?>'  >cryptoo.me</a>.
	</div>

	<div class="vp_trof_must_save"><?php _e( 'Please save', 'simple-bitcoin-faucets' ); ?></div>
	<?php submit_button(); ?>
</div>


<div id="sfbg_paidcontent_example" style="float: left; padding:10px; max-width:50%; padding-left:100px;">

<?php _e( 'Shortcodes', 'simple-bitcoin-faucets' ); ?>:
<br><br>
<code>[SBF_CONTENT_UNPAID]</code>
<?php _e( 'visible before payment, hidden after', 'simple-bitcoin-faucets' ); ?> 
<code>[/SBF_CONTENT_UNPAID]</code>
<br><?php _e( 'Attributes', 'simple-bitcoin-faucets' ); ?> :
<br>
<code>CONTENT_ID</code> - <?php _e( 'Alpha-numeric identifier of the content', 'simple-bitcoin-faucets' ); ?>.
<br><?php _e( 'Example', 'simple-bitcoin-faucets' ); ?> : 
<br><code>[SBF_CONTENT_UNPAID CONTENT_ID='content1']</code>
<?php _e( 'Full size picture will be available after payment', 'simple-bitcoin-faucets' ); ?>
<code>[/SBF_CONTENT_UNPAID]</code>
<hr>

<code>[SBF_CONTENT_PAID]</code>
<?php _e( 'hidden before payment, visible after', 'simple-bitcoin-faucets' ); ?> 
<code>[/SBF_CONTENT_PAID]</code>
<br><?php _e( 'Attributes', 'simple-bitcoin-faucets' ); ?> :
<br><code>CONTENT_ID</code> - <?php _e( 'Alpha-numeric identifier of the content, same as above', 'simple-bitcoin-faucets' ); ?>.
<br><?php _e( 'Example', 'simple-bitcoin-faucets' ); ?> : 
<br><code>[SBF_CONTENT_PAID CONTENT_ID='content1']</code>
&ltimg="hd_pictures/7593.jpg" /&gt
<code>[/SBF_CONTENT_PAID]</code>
<hr>

<code>[SBF_CONTENT_PAY_PROMPT]</code>
<?php _e( 'pay link text', 'simple-bitcoin-faucets' ); ?> 
<code>[/SBF_CONTENT_PAY_PROMPT]</code>
<br><?php _e( 'Attributes', 'simple-bitcoin-faucets' ); ?> :
<br><code>CONTENT_ID</code> - <?php _e( 'Alpha-numeric identifier of the content, same as above', 'simple-bitcoin-faucets' ); ?>.
<br><code>SATOSHI_AMOUNT</code> - <?php _e( 'Number. Amount of satoshi to pay', 'simple-bitcoin-faucets' ); ?>.
<br><code>VALID_SECONDS</code> - <?php _e( 'Number. Seconds the payment stays valid', 'simple-bitcoin-faucets' ); ?>.
<br><code>ALLOW_EDIT</code> - <?php _e( 'If TRUE the payer can edit the amount. Useful for donations', 'simple-bitcoin-faucets' ); ?>.

<br><?php _e( 'Example', 'simple-bitcoin-faucets' ); ?> 1 : 
<br><code>[SBF_CONTENT_PAID CONTENT_ID='content1' SATOSHI_AMOUNT=10 VALID_SECONDS=86400 ]</code>
pay 10 satoshi for 24 hours access
<code>[/SBF_CONTENT_PAID]</code>
<br><?php _e( 'Example', 'simple-bitcoin-faucets' ); ?> 2 : 
<br><code>[SBF_CONTENT_PAID CONTENT_ID='donation1' SATOSHI_AMOUNT=100 VALID_SECONDS=3600 ALLOW_EDIT=true]</code>
donate to this website
<code>[/SBF_CONTENT_PAID]</code>
<hr>


</div>




<div id='paidcontent_hints' style="clear:both;">
<hr>
<b><?php _e( 'Hints', 'simple-bitcoin-faucets' ); ?>:</b><br>
&nbsp;-&nbsp;
<?php _e('Same API Key can be used for registrations and page visits. However, using seperate Keys will simplify the track of performance, and increase the exposure of your website in the <a href="https://cryptoo.me/rotator/">App List</a> ', 'simple-bitcoin-faucets' ); ?>.
 <?php _e('History of the payments is available in the <a href="https://cryptoo.me/applications/">Application Manager</a> under "Payouts" link of your Application', 'simple-bitcoin-faucets' ); ?>.

<hr>

</div>

 
<script>

function paidcontent_tab_activated()
{
//do nothing for now
}

function paidcontent_check_api_key(selector)
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
	paidcontent_check_api_key('#sfbg_paidcontent_api_key');

	jQuery("#sfbg_paidcontent_api_key").on('change keyup paste', function () {
		referral_check_api_key('#sfbg_paidcontent_api_key');
	});
	
	jQuery("#sfbg_paidcontent_api_key").on('change', function () {
//		var new_val = jQuery("#sfbg_paidcontent_api_key").val();
//		if(old_val != new_val)
			jQuery(".vp_trof_must_save").show();
	});
	
});

</script>		
<?php

?>