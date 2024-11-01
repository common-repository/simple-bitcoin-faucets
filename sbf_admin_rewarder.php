<?php
?>

<link rel="stylesheet" href="<?php echo(plugin_dir_url( __FILE__ )) ?>rewarder/wme_rfsr.css">   
<script src="<?php echo(plugin_dir_url( __FILE__ )) ?>rewarder/wme_rfsr.js"></script> 
<script>
<?php
	echo($this->reward_shortcode_localize());
?>
</script>

<?php _e( 'Based on <a target=_blank href="https://wmexp.com/remotely-hosted-bitcoin-faucet-examples-list/example-surfer-reward/">Surfer Rewarder example</a>', 'simple-bitcoin-faucets' ); ?>.

<?php _e( 'Visitors are motivated to see more of the websites pages if once in a while they are rewarded by Bitcoin Faucet', 'simple-bitcoin-faucets' ); ?>.
<a href="javascript:document.getElementById('rewarder_hints').scrollIntoView();"><b><?php _e( 'Scroll to Hints', 'simple-bitcoin-faucets' ); ?></b></a>
<hr>
		<?php submit_button(); ?>
		<?php _e( 'Rewarder <b>Faucet ID</b>', 'simple-bitcoin-faucets' ); ?>:
		<input type="text" id='sfbr_faucet_id' name='sfbr_faucet_id' maxlength="10" 
		value='<?php echo esc_attr( get_option('sfbr_faucet_id','123456') ); ?>' >
		</input> 
		<hr>
		<p><?php _e( 'Use Shortcode', 'simple-bitcoin-faucets' ); ?> : <code>[SBFR]</code>
		<?php _e( 'anywhere on the pages you want ', 'simple-bitcoin-faucets' ); ?>
		<a href='javascript:sfb_r_hightlight_mark()' onmouseover='sfb_r_hightlight_mark()' ><?php _e( 'the Rewarder mark', 'simple-bitcoin-faucets' ); ?></a>
		<?php _e( 'to appear ( hint: a Widget is a good place )', 'simple-bitcoin-faucets' ); ?>,
		<?php _e( 'or', 'simple-bitcoin-faucets' ); ?>,
		
		<br><input type="checkbox" id='sfbr_include_all_pages' name='sfbr_include_all_pages' 
		<?php  checked('on', get_option('sfbr_include_all_pages',''),true ); ?> >
		</input> 
		&nbsp;<?php _e( 'Include into all website pages', 'simple-bitcoin-faucets' ); ?> (<?php _e( 'recommended', 'simple-bitcoin-faucets' ); ?>) . 
		<hr>
		<?php _e( 'Rewarder mark size', 'simple-bitcoin-faucets' ); ?> :
		<input type="number" min="16" max="128" id='sfbr_mark_size' name='sfbr_mark_size' maxlength="2"  
		value='<?php echo esc_attr( get_option('sfbr_mark_size','40') ); ?>' >
		</input> 
		<hr>
		<?php _e( 'Rewarder mark horizontal position', 'simple-bitcoin-faucets' ); ?> :
		<select id='sfbr_mark_h_position' name='sfbr_mark_h_position' >
			<option value='left' <?php if(get_option('sfbr_mark_h_position','right') == 'left')echo('selected'); ?>  >left</option>
			<option value='right' <?php if(get_option('sfbr_mark_h_position','right') == 'right')echo('selected'); ?> >right</option>
		</select>
		<hr>
		<?php _e( 'Rewarder mark vertical position', 'simple-bitcoin-faucets' ); ?> :
		<select id='sfbr_mark_v_position' name='sfbr_mark_v_position' >
			<option value='top' <?php if(get_option('sfbr_mark_v_position','top') == 'top')echo('selected'); ?>  >top</option>
			<option value='bottom' <?php if(get_option('sfbr_mark_v_position','top') == 'bottom')echo('selected'); ?> >botom</option>
		</select>		
		<hr>
		<?php _e( 'Rewarder mark horizontal offset', 'simple-bitcoin-faucets' ); ?> :
		<input type="number" min="0" max="200" id='sfbr_mark_h_offset' name='sfbr_mark_h_offset' maxlength="3"  
		value='<?php echo esc_attr( get_option('sfbr_mark_h_offset','40') ); ?>' >
		</input> 
		<hr>
		<?php _e( 'Rewarder mark vertical offset', 'simple-bitcoin-faucets' ); ?> :
		<input type="number" min="0" max="200" id='sfbr_mark_v_offset' name='sfbr_mark_v_offset' maxlength="3"  
		value='<?php echo esc_attr( get_option('sfbr_mark_v_offset','140') ); ?>' >
		</input> 		
		<hr>
		<?php _e( 'Minimum pages to visit before the Reward is given', 'simple-bitcoin-faucets' ); ?> :
		<input type="number" min="1" max="20" id='sfbr_pages_to_visit' name='sfbr_pages_to_visit' maxlength="3"  
		value='<?php echo esc_attr( get_option('sfbr_pages_to_visit','10') ); ?>' >
		</input> 		
		<hr>
		<?php _e( 'Minimum seconds to stay on a page to count', 'simple-bitcoin-faucets' ); ?> :
		<input type="number" min="1" max="30" id='sfbr_seconds_on_page' name='sfbr_seconds_on_page' maxlength="3"  
		value='<?php echo esc_attr( get_option('sfbr_seconds_on_page','5') ); ?>' >
		</input> 		
		<hr>
		<?php _e( 'Count repeated pages', 'simple-bitcoin-faucets' ); ?> :
		<select id='sfbr_allow_repeats' name='sfbr_allow_repeats' >
			<option value='true' <?php if(get_option('sfbr_allow_repeats','true') == 'true')echo('selected'); ?>  ><?php _e( 'Yes', 'simple-bitcoin-faucets' ); ?></option>
			<option value='false' <?php if(get_option('sfbr_allow_repeats','true') == 'false')echo('selected'); ?> ><?php _e( 'No', 'simple-bitcoin-faucets' ); ?></option>
		</select>	
		<hr>		
		<?php _e( 'Count reloaded pages', 'simple-bitcoin-faucets' ); ?> :
		<select id='sfbr_allow_reloads' name='sfbr_allow_reloads' >
			<option value='true' <?php if(get_option('sfbr_allow_reloads','false') == 'true')echo('selected'); ?>  ><?php _e( 'Yes', 'simple-bitcoin-faucets' ); ?></option>
			<option value='false' <?php if(get_option('sfbr_allow_reloads','false') == 'false')echo('selected'); ?> ><?php _e( 'No', 'simple-bitcoin-faucets' ); ?></option>
		</select>			
		
		
		<?php submit_button(); ?>
<hr>
<div id='rewarder_hints'>
<b><?php _e( 'Hints', 'simple-bitcoin-faucets' ); ?>:</b><br>
&nbsp;-&nbsp;
<a target=_new href="<?php _e('https://www.youtube.com/watch?v=-f5ckdopgag&list=PLRv0B44q8TR8bWrEwtMd6e17oW8wdRVIv&index=1', 'simple-bitcoin-faucets' ); ?>"><?php _e('Watch HowTo video', 'simple-bitcoin-faucets' ); ?></a>.
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
<?php _e( 'For non-Wordpress pages of your website use the code', 'simple-bitcoin-faucets' ); ?>:
<br><code><?php 
$t = $this->reward_shortcode(0);
$t = trim($t,"\n");
$t = htmlentities($t);
$t = nl2br($t);
echo($t); 
?></code>
<hr>
</div>
<hr>	

<script>
function sfb_r_hightlight_mark()
{
	if(jQuery('#wme_sr_inner iframe').length == 0)
	{
		jQuery("#wme_sr_mark").effect("shake", { direction: 'left',times:1,distance:50 },500 ).effect("shake", { direction: 'up',times:1,distance:50 },500 );
	}
}


function sfb_r_before_off()
{
	alert('<?php _e( 'This functionality is disabled in the administrative interface', 'simple-bitcoin-faucets' ); ?>');
	return false;
}


function sfb_r_apply_params()
{
//	RemoteFaucetSurferReward.removeMark();
	RemoteFaucetSurferReward.faucet_extra_styles = 'width:500px;';
	RemoteFaucetSurferReward.faucet_id = jQuery('#sfbr_faucet_id').val(); //this faucet is going to be shown 			
	RemoteFaucetSurferReward.pages_to_visit = jQuery('#sfbr_pages_to_visit').val(); //surfer must visit pages to get rewarded
	RemoteFaucetSurferReward.seconds_on_page = jQuery('#sfbr_seconds_on_page').val(); //surfer must stay seconds on page to count page as visited
	RemoteFaucetSurferReward.allow_reloads = jQuery('#sfbr_allow_reloads').val(); //if true; reloads count as page views 
	RemoteFaucetSurferReward.allow_repeats = jQuery('#sfbr_allow_repeats').val(); //if true; visits to the same page count
//settings for the box
	RemoteFaucetSurferReward.box_size = jQuery('#sfbr_mark_size').val()  + 'px'; //this.box_size x this.box_size 24px;36px;40px; etc.
	RemoteFaucetSurferReward.horizontal_side = jQuery('#sfbr_mark_h_position').val(); //may be 'left';'right'
	RemoteFaucetSurferReward.horizontal_offset = jQuery('#sfbr_mark_h_offset').val() + 'px'; //
	RemoteFaucetSurferReward.vertical_side = jQuery('#sfbr_mark_v_position').val(); //may be 'top'; 'bottom'
	RemoteFaucetSurferReward.vertical_offset = jQuery('#sfbr_mark_v_offset').val()  + 'px';//
//	RemoteFaucetSurferReward.initMark();
//console.log(RemoteFaucetSurferReward);
}


jQuery("#sfbr_pages_to_visit, #sfbr_seconds_on_page, #sfbr_mark_size, #sfbr_mark_h_offset,#sfbr_mark_v_offset").keypress(function (evt) {
    evt.preventDefault();
});

jQuery("#sfbr_pages_to_visit, #sfbr_seconds_on_page, #sfbr_mark_size, #sfbr_mark_h_offset,#sfbr_mark_v_offset").on('change', function () {
	sfb_r_apply_params();
	RemoteFaucetSurferReward.positionMark();
})


jQuery("#sfbr_mark_h_position, #sfbr_mark_v_position").on('change', function () {
	sfb_r_apply_params();
	RemoteFaucetSurferReward.positionMark();
	sfb_r_hightlight_mark();
})

jQuery("#sfbr_allow_repeats").on('change', function () {
	var s = jQuery(this).val();

	if(s == 'false'){
		jQuery("#sfbr_allow_reloads").val('false');
		jQuery("#sfbr_allow_reloads option[value='true']").attr('disabled', true); 
	}else{
		jQuery("#sfbr_allow_reloads option[value='true']").attr('disabled', false); 
	}
})
jQuery('#sfbr_allow_repeats').trigger('change');
RemoteFaucetSurferReward.faucet_before_off_function = 'sfb_r_before_off';

var t_hider = setInterval(function(){
	if(localStorage.getItem("sfbs_cur_tab") == 'rewarder')
	{
		clearInterval(t_hider);
	}
	else
	{
		if(jQuery('#wme_sr_mark').is(':visible'))
		{
			jQuery('#wme_sr_mark').hide();
			clearInterval(t_hider);
		}
	}
},200);

jQuery(document).ready(function() {
sfb_r_apply_params();
RemoteFaucetSurferReward.positionMark();
});
</script>		
<?php
//echo($this->reward_shortcode( 0 ));
?>