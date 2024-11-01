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
<?php _e( 'Satoshi Bonds can be used as prizes, gifts, or sold as digital goods in stores/marketplaces', 'simple-bitcoin-faucets' ); ?>.
<?php _e( 'Person who has the Bond Code can redeem it for satoshi on the website issued the Bond', 'simple-bitcoin-faucets' ); ?>.
<br>
<?php _e( 'Use Shortcode', 'simple-bitcoin-faucets' ); ?>  <code>[SBFG_BOND_REDEEM]</code>
 <?php _e( 'where you want the Bond Redeem form to appear', 'simple-bitcoin-faucets' ); ?> , 
 <?php _e( 'or', 'simple-bitcoin-faucets' ); ?>
 <a href="#" onclick="window.open(top.location.href+'&shortcode=[SBFG_BOND_REDEEM]&name=Redeem%Satoshi%Bonds');return false;"><?php _e('Generate test Page', 'simple-bitcoin-faucets' ); ?></a>


<br><a href="javascript:document.getElementById('bonds_hints').scrollIntoView();"><b><?php _e( 'Scroll to Hints', 'simple-bitcoin-faucets' ); ?></b></a>


<hr>
		
<div id="sfbg_referral_settings" style="float: left; padding:10px;">	
	
	<div class='bonds_comments'>
		<?php _e( 'Get the API Key for free at', 'simple-bitcoin-faucets' ); ?> 
		<a target=_blank href='<?php _e( 'https://cryptoo.me/applications/', 'simple-bitcoin-faucets' ); ?>'  >cryptoo.me</a>.
	</div>	
	<?php _e( 'Cryptoo.me <b>API Key</b>', 'simple-bitcoin-faucets' ); ?>:
	<input type="text" id='sfbg_bonds_api_key' name='sfbg_bonds_api_key' maxlength="40" 
	value='<?php echo esc_attr( get_option('sfbg_bonds_api_key','') ); ?>' >
	</input> 
    <?php 
	SBF_DB_update_user_api_key(esc_attr( get_option('sfbg_bonds_api_key',''))); 
	
	?>

	<div class="vp_trof_must_save" ><?php _e( 'Please save', 'simple-bitcoin-faucets' ); ?></div>
	<?php submit_button(); ?>
	<!-- TO DO - STATS! -->
	<div id="bond_stats" style="display:none; width:100%; border:1px solid black;">
		<span id="bond_stats_bonds">999999999</span>
		<?php _e( 'bonds for', 'simple-bitcoin-faucets' )  ?>
		<span id="bond_stats_satoshi_in_bonds">999999999</span>
		<?php _e( 'satoshi', 'simple-bitcoin-faucets' )  ?>
		(<span id="bond_stats_satoshi_total">999999999</span>
		<?php _e( 'satoshi', 'simple-bitcoin-faucets' )  ?>
		<?php _e( 'available', 'simple-bitcoin-faucets' )  ?>)
	</div>
</div>


<div id="sfbg_bonds_example" style="float: left; padding:10px; max-width:50%; padding-left:100px;">

<?php _e( 'Redeem Bonds form example', 'simple-bitcoin-faucets' ); ?>:
<br><br>
<div style='border:1px solid gray;padding:5px;margin:5px;width:400px;'><?php echo(SBF_DB_render_bonds_box()); ?></div>

</div>

<div style='width:100%;clear:both;'>
<hr><hr>
</div>

<div id='bonds_list_top_wrap'>
<table width=98% border=0>
<tr>
<td width=100%><h1><?php _e( 'Bonds', 'simple-bitcoin-faucets' ); ?></h1></td>
<td nowrap align=right>


<div id='bonds_create_dlg_wrap' style="display:none">
<div class="container" id='bonds_create_dlg_template'>
<table id='bonds_create_new_tpl' >
<tr>
<td align="right"><?php _e( 'Block name', 'simple-bitcoin-faucets' )  ?>:</td>
<td><input  class="SBF_DB_bond_block_dlg"  maxlength=100 size=50 type='text' placeholder="<?php _e( 'may not be empty', 'simple-bitcoin-faucets' )  ?>"></input></td>
</tr>
<tr>
<td align="right"><?php _e( 'Bonds in block', 'simple-bitcoin-faucets' )  ?>:</td>
<td><input  class='SBF_DB_bond_quantity_dlg SBF_DB_bond_num' value="100" maxlength=3 size=3 type='text'></input><?php _e( 'bonds', 'simple-bitcoin-faucets' );  ?></td>
</tr>
<tr>
<td align="right"><?php _e( 'Value of each bond', 'simple-bitcoin-faucets' )  ?>:</td>
<td><input class='SBF_DB_bond_amount_dlg SBF_DB_bond_num' value="10" maxlength=8 size=8 type='text'></input><?php _e( 'satoshi', 'simple-bitcoin-faucets' );  ?></td>
</tr>
<tr>
<td align="right"><?php _e( 'Total Block cost', 'simple-bitcoin-faucets' )  ?>:</td>
<td><input class='SBF_DB_block_total_dlg' value="1000" maxlength=15 size=15 type='text' disabled></input><?php _e( 'satoshi', 'simple-bitcoin-faucets' );  ?></td>
</tr>
<tr><td> </td><td> </td></tr>
</table>
</div>
</div>


<button type='button'  class='btn btn-success btn-lg' id='SBF_DB_add_block_dlg_starter' onclick='SBF_DB_adm_add_block_dlg();return false'><span class='glyphicon glyphicon-plus'></span> <?php _e( 'Create Bond Block', 'simple-bitcoin-faucets' ) ?></button>
<button type='button'  class='btn btn-info btn-lg' id='SBF_DB_add_block_dlg_starter' onclick='bonds_update_list();return false'><span class='glyphicon glyphicon-refresh'></span> <?php _e( 'Reload', 'simple-bitcoin-faucets' ) ?></button></td>
</td>
</tr>
</table>
</div>

<div id='bonds_list_wrap' class="container"></div>



<div id='bonds_hints' style="clear:both;">
<hr>
<b><?php _e( 'Hints', 'simple-bitcoin-faucets' ); ?>:</b><br>
&nbsp;-&nbsp;
<a target=_new href="<?php _e('https://www.youtube.com/watch?v=kRpEjPxKICA&list=PLRv0B44q8TR8bWrEwtMd6e17oW8wdRVIv&index=2', 'simple-bitcoin-faucets' ); ?>"><?php _e('Watch Bonds HowTo video', 'simple-bitcoin-faucets' ); ?></a>.<br>
&nbsp;-&nbsp;
<a target=_new href="<?php _e('https://www.youtube.com/watch?v=v0WAz8OyY28&list=PLRv0B44q8TR8bWrEwtMd6e17oW8wdRVIv&index=4', 'simple-bitcoin-faucets' ); ?>"><?php _e('Watch Feeder HowTo video', 'simple-bitcoin-faucets' ); ?></a>.<br>
&nbsp;-&nbsp;
<?php _e('Reputation is everything. Always make sure you have enough funds to cover all outstanding bonds', 'simple-bitcoin-faucets'); ?>.
<hr>&nbsp;-&nbsp;
<?php _e('You can use <code>&bond=BONDCODE</code> and <code>&to=BITCOINADDRESS</code> flags in the URL of the page, containing the redeem form, like ', 'simple-bitcoin-faucets'); ?>
<code><?php echo(get_site_url())?>/REDEEM_PAGE/?bond=123456789</code>
.
<hr>&nbsp;-&nbsp;
<?php _e('Same API Key can be used for registrations and page visits. However, using seperate Keys will simplify the track of performance, and increase the exposure of your website in the <a href="https://cryptoo.me/rotator/">App List</a> ', 'simple-bitcoin-faucets' ); ?>.
 <?php _e('History of the payments is available in the <a href="https://cryptoo.me/applications/">Application Manager</a> under "Payouts" link of your Application', 'simple-bitcoin-faucets' ); ?>.

<hr>

</div>
<script>
SBF_DB_adm_add_block_dlg = function(){
	var SBF_DB_bond_inner  = jQuery('#bonds_create_dlg_template').clone().attr({ id: 'SBF_DB_dlg', "class": 'SBF_DB_dlg',});

	jQuery.MessageBox({
		buttonDone      : "<?php _e( 'Create Bond Block', 'simple-bitcoin-faucets' ) ?>",
		buttonFail      : "<?php _e( 'Cancel', 'simple-bitcoin-faucets' ) ?>",
		title         	: "<?php _e( 'Bond Block Options', 'simple-bitcoin-faucets' ) ?>",
		input : SBF_DB_bond_inner,
		
		filterDone      : function(data){
//			console.log(data);
			if (data[0].trim() === ""){
				jQuery('.SBF_DB_dlg .SBF_DB_bond_block_dlg').focus();
				return "<?php _e( 'Bond Block name my not be empty', 'simple-bitcoin-faucets' ) ?>";
			}
			if (parseInt(data[1].trim()) === 0){
				jQuery('.SBF_DB_dlg .SBF_DB_bond_quantity_dlg').focus();
				return "<?php _e( 'Value is out of range', 'simple-bitcoin-faucets' ) ?>";
			}	
			if (parseInt(data[2].trim()) === 0){
				jQuery('.SBF_DB_dlg .SBF_DB_bond_amount_dlg').focus();
				return "<?php _e( 'Value is out of range', 'simple-bitcoin-faucets' ) ?>";
			}	
//if we are here - data is ok. call ajax here when inputs exist		
			SBF_DB_do_add_bond();
		}
	}).done(function(data){
//		alert("DONE " + data);
	});
}//SBF_DB_adm_add_block_dlg

function bonds_tab_activated()
{
//do nothing for now
}

function bonds_check_api_key(selector)
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
	bonds_check_api_key('#sfbg_bonds_api_key');
	
	jQuery("#sfbg_bonds_api_key").on('change keyup paste', function () {
		referral_check_api_key('#sfbg_bonds_api_key');
	});
	
	jQuery("#sfbg_bonds_api_key").on('change', function () {
		jQuery(".vp_trof_must_save").show();
	});	


	jQuery('body').on('change keyup paste focusout focusin', '.SBF_DB_dlg .SBF_DB_bond_block_dlg', function() {
		if(jQuery(this).val().trim().length === 0){
			jQuery(this).css('background-color','LavenderBlush');
		}else{
			jQuery(this).css('background-color','HoneyDew');
		}
	});
	
	jQuery('body').on('change keyup paste focusout focusin', '.SBF_DB_dlg .SBF_DB_bond_num', function() {
//	jQuery(".SBF_DB_bond_num").on('change keyup paste focusout focusin', function () {
		var s = jQuery(this).val().trim();
		var n = s.replace(/[^0-9]/g,'');
		jQuery(this).val(n);	
		if(n.length == 0)
		{
			jQuery(this).val('0');
		}
		if(jQuery(this).val() == 0){
			jQuery(this).css('background-color','LavenderBlush');
		}else{
			jQuery(this).css('background-color','HoneyDew');
		}

		jQuery(this).val(parseInt(jQuery(this).val()));	
		
		if(jQuery('.SBF_DB_dlg .SBF_DB_bond_block_dlg').val().trim().length === 0){
			jQuery('.SBF_DB_dlg .SBF_DB_bond_block_dlg').css('background-color','LavenderBlush');
		}else{
			jQuery('.SBF_DB_dlg .SBF_DB_bond_block_dlg').css('background-color','HoneyDew');
		}		
		
		q = parseInt(jQuery('.SBF_DB_dlg .SBF_DB_bond_quantity_dlg').val());
		a = parseInt(jQuery('.SBF_DB_dlg .SBF_DB_bond_amount_dlg').val());
		jQuery('.SBF_DB_dlg .SBF_DB_block_total_dlg').val(q * a);

	});
	bonds_update_list();	
});
</script>
<?php
require_once( plugin_dir_path( __FILE__ ).'/bonds/bonds_helper.php');
?>