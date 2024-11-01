<?php 
?>

<?php echo($this->bubbleshooter_shortcode_localize()); ?>  
<?php echo($this->bubbleshooter_shortcode_top()) ?> 


 <a href="javascript:document.getElementById('bubbleshooter_hints').scrollIntoView();"><b><?php _e( 'Scroll to Hints', 'simple-bitcoin-faucets' ); ?></b></a>

<hr>
<?php _e( 'Use Shortcode', 'simple-bitcoin-faucets' ); ?>  <code>[SBFG_BUBBLESHOOTER]</code> 
 <?php _e( 'where you want the game to appear', 'simple-bitcoin-faucets' ); ?> , 
 <?php _e( 'or', 'simple-bitcoin-faucets' ); ?>
 <a href="#" onclick="window.open(top.location.href+'&shortcode=[SBFG_BUBBLESHOOTER]&name=BubbleShooter');return false;"><?php _e('Generate test Page', 'simple-bitcoin-faucets' ); ?></a>

		<hr>
		
<div id="sfbg_bubbleshooter_settings" style="float: left; padding:10px;">	

		<input sbf_game="bubbleshooter" type="hidden" id="sbfg_sf_h_bubbleshooter" name="sfbg_sf_bubbleshooter" class="sbfg_sf_h_bubbleshooter sbfg_sf_h" value="<?php echo esc_attr( get_option('sfbg_sf_bubbleshooter','') ); ?>"></input>
		<div sbf_game="bubbleshooter" id="sbfg_sf_default_bubbleshooter" style="display:none" class="sbfg_sf_default sbfg_sf_default_bubbleshooter">1000:123456,5000:123456,10000:123456,20000:123456,40000:123456,60000:123456</div>
		<div sbf_game_settings="bubbleshooter" style="display:none"></div>
		<div id="sbfg_sf_table_bubbleshooter" class="sbfg_sf_table" >
			<div id="sbfg_sf_table_header_bubbleshooter" class="sbfg_sf_table_header">
				<div class="sbfg_sf_table_header_field"><?php _e( 'Score', 'simple-bitcoin-faucets' ); ?></div>
				<div class="sbfg_sf_table_header_field"><?php _e( 'Faucet Id', 'simple-bitcoin-faucets' ); ?></div>
			</div>

			<div sbf_game="bubbleshooter" id="sbfg_sf_current_bubbleshooter" class="sbfg_sf_current sbfg_sf_current_bubbleshooter"></div>
		</div>
		<script>
			jQuery(document).ready(function() {
				sbf_sf_parse_from_options('bubbleshooter',false,true,true);
			});
		</script>
		
		<?php submit_button(); ?>
</div>

<div id="sfbg_bubbleshooter_example" style="float: left; padding:10px;">

<?php echo($this->bubbleshooter_shortcode_body()) ?> 

</div>


<div id='bubbleshooter_hints' style="clear:both;">
<hr>
<b><?php _e( 'Hints', 'simple-bitcoin-faucets' ); ?>:</b><br>
&nbsp;-&nbsp;
<a target=_new href="<?php _e('https://www.youtube.com/watch?v=-f5ckdopgag&list=PLRv0B44q8TR8bWrEwtMd6e17oW8wdRVIv&index=1', 'simple-bitcoin-faucets' ); ?>"><?php _e('Watch HowTo video', 'simple-bitcoin-faucets' ); ?></a>.
<hr>&nbsp;-&nbsp;
<?php _e( 'You may use same Faucet as a reward for different scores', 'simple-bitcoin-faucets' ); ?>.
 <?php _e( 'However remember - every Faucet is a link to your page', 'simple-bitcoin-faucets' ); ?>
 <?php _e( 'to bring more users from the Faucet Lists', 'simple-bitcoin-faucets' ); ?>
 <?php _e( 'like', 'simple-bitcoin-faucets' ); ?>
 <a target=_blank href="<?php _e( 'https://wmexp.com/', 'simple-bitcoin-faucets' ); ?>" ><?php _e( 'here', 'simple-bitcoin-faucets' ); ?></a>
 <?php _e( 'and', 'simple-bitcoin-faucets' ); ?>
 <a  target=_blank href="<?php _e( 'https://cryptoo.me/rotator/', 'simple-bitcoin-faucets' ); ?>"><?php _e( 'here', 'simple-bitcoin-faucets' ); ?></a>.


<hr>
</div>
	
	
<script>


</script>		
<?php

?>