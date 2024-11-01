<?php
?>

<link rel="stylesheet" href="<?php echo(plugin_dir_url( __FILE__ )) ?>blockrain/blockrain.css">   
<script src="<?php echo(plugin_dir_url( __FILE__ )) ?>blockrain/blockrain.jquery.js"></script>
<?php _e( 'Based on <a target=_blank href="https://wmexp.com/remotely-hosted-bitcoin-faucet-examples-list/example-game-bonuses/">Game Bonuses example</a>', 'simple-bitcoin-faucets' ); ?>.
 <a href="javascript:document.getElementById('blockrain_hints').scrollIntoView();"><b><?php _e( 'Scroll to Hints', 'simple-bitcoin-faucets' ); ?></b></a>

<hr>
<?php _e( 'Use Shortcode', 'simple-bitcoin-faucets' ); ?>  <code>[SBFG_BLOCKRAIN]</code> 
 <?php _e( 'where you want the game to appear', 'simple-bitcoin-faucets' ); ?> , 
 <?php _e( 'or', 'simple-bitcoin-faucets' ); ?>
 <a href="#" onclick="window.open(top.location.href+'&shortcode=[SBFG_BLOCKRAIN]&name=BlockRain');return false;"><?php _e('Generate test Page', 'simple-bitcoin-faucets' ); ?></a>

		<hr>
		
<div id="sfbg_br_settings" style="float: left; padding:10px;">	

		<input sbf_game="blockrain" type="hidden" id="sbfg_sf_h_blockrain" name="sfbg_sf_blockrain" class="sbfg_sf_h_blockrain sbfg_sf_h" value="<?php echo esc_attr( get_option('sfbg_sf_blockrain','') ); ?>"></input>
		<div sbf_game="blockrain" id="sbfg_sf_default_blockrain" style="display:none" class="sbfg_sf_default sbfg_sf_default_blockrain">1000:123456,5000:123456,10000:123456,20000:123456,30000:123456,40000:123456,50000:123456,70000:123456</div>
		<div sbf_game_settings="blockrain" style="display:none"></div>
		<div id="sbfg_sf_table_blockrain" class="sbfg_sf_table" >
			<div id="sbfg_sf_table_header_blockrain" class="sbfg_sf_table_header">
				<div class="sbfg_sf_table_header_field"><?php _e( 'Score', 'simple-bitcoin-faucets' ); ?></div>
				<div class="sbfg_sf_table_header_field"><?php _e( 'Faucet Id', 'simple-bitcoin-faucets' ); ?></div>
			</div>

			<div sbf_game="blockrain" id="sbfg_sf_current_blockrain" class="sbfg_sf_current sbfg_sf_current_blockrain"></div>
		</div>
		<script>
			jQuery(document).ready(function() {
				sbf_sf_parse_from_options('blockrain',false,true,true);
			});
		</script>
		
		<?php submit_button(); ?>
</div>

<div id="sfbg_br_example" style="float: left; padding:10px;">
<script>
<?php echo($this->blockrain_shortcode_localize()); ?>
</script>
<div class="sfbg_br_game_wrap" style="width:400px; height:500px; border:0px dotted gray;"><center>
<div class="sfbg_br_game" style="width:250px; height:500px;"></div>
<div id='sfbg_br_faucet-TO-BE' style='display:none;width:400px;min-height:400px;'></div></center></div>
<script src="<?php echo(plugin_dir_url( __FILE__ )) ?>blockrain/starter.js"></script>
</div>


<div id='blockrain_hints' style="clear:both;">
<hr>
<b><?php _e( 'Hints', 'simple-bitcoin-faucets' ); ?>:</b><br>
&nbsp;-&nbsp;
<a target=_new href="<?php _e('https://www.youtube.com/watch?v=-f5ckdopgag&list=PLRv0B44q8TR8bWrEwtMd6e17oW8wdRVIv&index=1', 'simple-bitcoin-faucets' ); ?>"><?php _e('Watch HowTo video', 'simple-bitcoin-faucets' ); ?></a>.
<hr>&nbsp;-&nbsp;
<?php _e( 'Do not combine several games using keyboard input in the same page', 'simple-bitcoin-faucets' ); ?>
 <?php _e( '(like BlockRain and 2048)', 'simple-bitcoin-faucets' ); ?>.
<hr>
&nbsp;-&nbsp;
 <?php _e( 'Make sure you have proper favicons to look nice', 'simple-bitcoin-faucets' ); ?>
 <?php _e( 'in the Faucet Lists', 'simple-bitcoin-faucets' ); ?>
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