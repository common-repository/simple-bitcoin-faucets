<?php

?>
<?php _e( 'Based on <a target=_blank href="https://wmexp.com/remotely-hosted-bitcoin-faucet-examples-list/example-creating-remote-faucet/">Creating Remote Faucet example</a>', 'simple-bitcoin-faucets' ); ?>.
 <a href="javascript:document.getElementById('faucets_hints').scrollIntoView();"><b><?php _e( 'Scroll to Hints', 'simple-bitcoin-faucets' ); ?></b></a>
<hr>	
		<?php _e( 'Enter <b>Faucet ID</b>:', 'simple-bitcoin-faucets' ); ?> 
		<input type="text" id='sfbs_faucet_id' maxlength="10"  value='' autocomplete="on"></input>
		<div id='sfbs_has_shortcode_faucets'>
			<p><?php _e( 'Use Shortcode:', 'simple-bitcoin-faucets' ); ?> <code>[SBFS fid=<span id='sfbs_shortcode'>123456</span>]</code>
			<?php _e( 'where you want the Faucet to appear', 'simple-bitcoin-faucets' ); ?>,
			<?php _e( 'or', 'simple-bitcoin-faucets' ); ?>
			<a href="#" onclick="window.open(top.location.href+'&fid='+jQuery('#sfbs_shortcode').html()+'&t=1');return false;"><?php _e('Generate simple Faucet Page', 'simple-bitcoin-faucets' ); ?></a>
			<hr>
			<h3><?php _e( 'Extra', 'simple-bitcoin-faucets' ); ?></h3>
			<p><a href="#" onclick="window.open(top.location.href+'&fid='+jQuery('#sfbs_shortcode').html()+'&t=2');return false;"><?php _e('Generate simple Faucet Page (narrow content)', 'simple-bitcoin-faucets' ); ?></a>
			<p><a href="#" onclick="window.open(top.location.href+'&fid='+jQuery('#sfbs_shortcode').html()+'&t=3');return false;"><?php _e('Generate simple Faucet Page (extra fixed ads)', 'simple-bitcoin-faucets' ); ?></a>
			<p><a href="#" onclick="window.open(top.location.href+'&fid='+jQuery('#sfbs_shortcode').html()+'&t=4');return false;"><?php _e('Generate simple Faucet Page (narrow content,extra fixed ads)', 'simple-bitcoin-faucets' ); ?></a>				
		</div><!-- sfbs_has_shortcode_faucets -->
		<hr>

<div id='faucets_hints'>
<b><?php _e( 'Hints', 'simple-bitcoin-faucets' ); ?>:</b><br>
&nbsp;-&nbsp;
<a target=_new href="<?php _e('https://www.youtube.com/watch?v=-f5ckdopgag&list=PLRv0B44q8TR8bWrEwtMd6e17oW8wdRVIv&index=1', 'simple-bitcoin-faucets' ); ?>"><?php _e('Watch HowTo video', 'simple-bitcoin-faucets' ); ?></a>.
<hr>&nbsp;-&nbsp;
<?php _e( 'On the WordPress installation you can use as many Faucets as you want', 'simple-bitcoin-faucets' ); ?>.
<hr>&nbsp;-&nbsp;
<?php _e( 'Use', 'simple-bitcoin-faucets' ); ?> 
 <code><?php echo(get_site_url())?></code> 
 <?php _e( 'as Faucet/App ULR for your Faucets', 'simple-bitcoin-faucets' ); ?>.
<hr>
&nbsp;-&nbsp;
<?php _e( 'While configuring real Faucets in the Faucet Manager, come up with attractive Faucet Names', 'simple-bitcoin-faucets' ); ?>
 <?php _e( 'to bring more users from the Faucet Lists', 'simple-bitcoin-faucets' ); ?>,
 <?php _e( 'like', 'simple-bitcoin-faucets' ); ?>
 <a target=_blank href="<?php _e( 'https://wmexp.com/', 'simple-bitcoin-faucets' ); ?>" ><?php _e( 'here', 'simple-bitcoin-faucets' ); ?></a>
 <?php _e( 'and', 'simple-bitcoin-faucets' ); ?>
 <a  target=_blank href="<?php _e( 'https://cryptoo.me/rotator/', 'simple-bitcoin-faucets' ); ?>"><?php _e( 'here', 'simple-bitcoin-faucets' ); ?></a>.
<hr>
&nbsp;-&nbsp;
<?php _e( 'For non-Wordpress pages of your website use the code', 'simple-bitcoin-faucets' ); ?>
 <span id='sfbs_code1'></span>
 <?php _e( 'where you want the Faucet to appear', 'simple-bitcoin-faucets' ); ?>.
<?php 
$t = $this->faucets_shortcode(array(fid => "{THINGIE_TO_REPLACE}"));
$t = trim($t,"\n");
$t = htmlentities($t);
$t = nl2br($t);
echo("<div id='sfbs_e1' style='display:none;'><code>$t</code></div>"); //example of code
?>
<hr>
</div>
	

		
<script>
function sfbs_switch_faucets_init()
{
	var sfbs_cur_fid = localStorage.getItem("cur_fid");
	if(sfbs_cur_fid == null)
	{
		sfbs_cur_fid = '123456';
	}
	jQuery("#sfbs_faucet_id").val(sfbs_cur_fid).select();
	jQuery("#sfbs_faucet_id").on('change keyup paste', function () {
		var s = jQuery(this).val()
		var n = s.replace(/[^0-9]/g,'');
		jQuery(this).val(n);	
		if(n.length == 0)
		{
			jQuery("#sfbs_has_shortcode_faucets").css('display','none');
		}
		else
		{
			jQuery("#sfbs_has_shortcode_faucets").css('display','block');
			jQuery("#sfbs_shortcode").html(n);
		jQuery('#sfbs_code1').html(jQuery('#sfbs_e1').html().replace(/{THINGIE_TO_REPLACE}/g,n) );				
			localStorage.setItem("cur_fid",n);
		}
	});
}//sfbs_switch_faucets_init
sfbs_switch_faucets_init();
jQuery('#sfbs_faucet_id').trigger('change');
</script>