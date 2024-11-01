<?php
?>
<script>
<?php echo($this->main_js_shortcode_localize()); ?>
</script>
<?php echo($this->videopoker_shortcode_localize()); ?>  
<?php echo($this->videopoker_shortcode_top()) ?> 

 <a href="javascript:document.getElementById('videopoker_hints').scrollIntoView();"><b><?php _e( 'Scroll to Hints', 'simple-bitcoin-faucets' ); ?></b></a>

<hr>

<?php _e( 'Use Shortcode', 'simple-bitcoin-faucets' ); ?>  <code>[SBFG_VIDEOPOKER]</code>
 <?php _e( 'where you want the game to appear', 'simple-bitcoin-faucets' ); ?> , 
 <?php _e( 'or', 'simple-bitcoin-faucets' ); ?>
 <a href="#" onclick="window.open(top.location.href+'&shortcode=[SBFG_VIDEOPOKER]&name=Video%20Poker');return false;"><?php _e('Generate test Page', 'simple-bitcoin-faucets' ); ?></a>

		<hr>
		
<div id="sfbg_videopoker_settings" style="float: left; padding:10px;">	
	<?php submit_button(); ?>
	<div class="vp_trof_must_save" style='background:red;color:yellow;display:none'><?php _e( 'Please save', 'simple-bitcoin-faucets' ); ?></div>
	
	
	<?php _e( 'Cryptoo.me <b>API Key</b>', 'simple-bitcoin-faucets' ); ?>:
	<input type="text" id='sfbg_sf_videopoker_api_key' name='sfbg_sf_videopoker_api_key' maxlength="40" 
	value='<?php echo esc_attr( get_option('sfbg_sf_videopoker_api_key','') ); ?>' >
	</input> 
	<div class='videopoker_comments'>
		<?php _e( 'Get the API Key for free at', 'simple-bitcoin-faucets' ); ?> 
		<a target=_blank href='<?php _e( 'https://cryptoo.me/applications/', 'simple-bitcoin-faucets' ); ?>'  >cryptoo.me</a>.
	</div>
	<hr>

	<?php _e( 'Maximum bet', 'simple-bitcoin-faucets' ); ?>:
	<input type="text" min="1" max="10000" size=5 class='trof_num' id='sfbg_sf_videopoker_maximum_bet' name='sfbg_sf_videopoker_maximum_bet' maxlength="10" 
	value='<?php echo esc_attr( get_option('sfbg_sf_videopoker_maximum_bet','1000') ); ?>' >
	</input> 
	<?php _e( 'satoshi', 'simple-bitcoin-faucets' ); ?>
	<div class='videopoker_comments'>
		<?php _e( 'Limits the losses from very lucky player', 'simple-bitcoin-faucets' ); ?>
	</div>
	<hr>

	<?php _e( 'Initial bonus', 'simple-bitcoin-faucets' ); ?>:
	<input type="text" min="0" max="100" size=3 class='trof_num' id='sfbg_sf_videopoker_minimum_initial_bonus' name='sfbg_sf_videopoker_minimum_initial_bonus' maxlength="10" 
	value='<?php echo esc_attr( get_option('sfbg_sf_videopoker_minimum_initial_bonus','3') ); ?>' >
	</input>&nbsp;-&nbsp;<input type="text" min="0" max="100" size=3 class='trof_num' id='sfbg_sf_videopoker_maximum_initial_bonus' name='sfbg_sf_videopoker_maximum_initial_bonus' maxlength="10" 
	value='<?php echo esc_attr( get_option('sfbg_sf_videopoker_maximum_initial_bonus','11') ); ?>' >
	</input> 	
	<?php _e( 'satoshi', 'simple-bitcoin-faucets' ); ?>
	<div class='videopoker_comments'>
		<?php _e( 'Random bonus for new player', 'simple-bitcoin-faucets' ); ?>
	</div>
	
	<hr>
	<?php _e( 'Bonuses before deposit', 'simple-bitcoin-faucets' ); ?>:
	<input type="text" min="0" max="100" size=3 class='trof_num' id='sfbg_sf_videopoker_bonuses_before_deposit' name='sfbg_sf_videopoker_bonuses_before_deposit' maxlength="10" 
	value='<?php echo esc_attr( get_option('sfbg_sf_videopoker_bonuses_before_deposit','3') ); ?>' >
	</input> 	
	<div class='videopoker_comments'>
		<?php _e( 'How many times initial bonus is offered', 'simple-bitcoin-faucets' ); ?> <br>
		<?php _e( 'before player has to deposit own satoshi', 'simple-bitcoin-faucets' ); ?>
	</div>
	<hr>	
	
	<?php _e( 'Wins before withdraw', 'simple-bitcoin-faucets' ); ?>:
	<input type="text" min="1" max="100" size=4 class='trof_num' id='sfbg_sf_videopoker_wins_before_withdraw' name='sfbg_sf_videopoker_wins_before_withdraw' maxlength="10" 
	value='<?php echo esc_attr( get_option('sfbg_sf_videopoker_wins_before_withdraw','3') ); ?>' >
	</input> 
	<div class='videopoker_comments'>
		<?php _e( 'While playing on bonus money', 'simple-bitcoin-faucets' ); ?>, <br>
		<?php _e( 'the player has to win several times', 'simple-bitcoin-faucets' ); ?>
	</div>
	<hr>
	
	<input type="hidden" name='sfbg_sf_videopoker_maximum_deposit' value='1000000' ></input>
	<input type="hidden" name='sfbg_sf_videopoker_minimum_deposit' value='5' ></input>
	<input type="hidden" name='sfbg_sf_videopoker_balance_page_leave_confirm' value='1' ></input>
	<input type="hidden" name='sfbg_sf_videopoker_stop_if_adblock' value='0' ></input>

	<?php submit_button(); ?>
</div>
<div id="sfbg_videopoker_example" style="float: left; padding:10px;">
<script>
</script>

<?php echo($this->videopoker_shortcode_body()) ?> 


</div>






<div id='videopoker_hints' style="clear:both;">
<hr>
<b><?php _e( 'Hints', 'simple-bitcoin-faucets' ); ?>:</b><br>
&nbsp;-&nbsp;
<?php _e('On long run the house (the website, you) always wins, but it would be wise to keep some satshi on the account to pay the lucky player', 'simple-bitcoin-faucets' ); ?>.
<hr>
&nbsp;-&nbsp;
<?php _e('<a target=_blank href="https://en.wikipedia.org/wiki/Video_poker">Know your game</a>', 'simple-bitcoin-faucets' ); ?>.
<hr>

</div>

 
<script>

function videopoker_tab_activated()
{
	vp_trof_update();
}

function videopoker_check_api_key()
{
	var o = jQuery('#sfbg_sf_videopoker_api_key');
	var b_pref = '0';
	if(o.val().length < 40)
	{
		b_pref = '1';
	}
	o.css('border',b_pref + 'px solid red');
}

jQuery(document).ready(function () {
	videopoker_check_api_key();

	jQuery("#sfbg_sf_videopoker_api_key").on('change keyup paste', function () {
		videopoker_check_api_key();
	});
	
	var no_exit_popup = true; //no popup on page reload
	
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
	
	jQuery("#sfbg_sf_videopoker_maximum_bet").on('change', function () {
		var n = parseInt(jQuery(this).val());
		if(n == 0)
		{
			jQuery(this).val('1');
		}
	});

	jQuery("#sfbg_sf_videopoker_maximum_initial_bonus").on('change', function () {
		var n = parseInt(jQuery(this).val());
		if(n < parseInt(jQuery("#sfbg_sf_videopoker_minimum_initial_bonus").val()) )
		{
			jQuery(this).val(jQuery("#sfbg_sf_videopoker_minimum_initial_bonus").val());
		}
	});

	jQuery("#sfbg_sf_videopoker_minimum_initial_bonus").on('change', function () {
		var n = parseInt(jQuery(this).val());
		if(n > parseInt(jQuery("#sfbg_sf_videopoker_maximum_initial_bonus").val()) )
		{
			jQuery(this).val(jQuery("#sfbg_sf_videopoker_maximum_initial_bonus").val());
		}
	});
	
	jQuery("#sfbg_videopoker_settings input").on('change', function () {
		jQuery(".vp_trof_must_save").show();
	});

});

</script>		
<?php

?>