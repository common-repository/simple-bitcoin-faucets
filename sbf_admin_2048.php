<?php
?>


<link rel="stylesheet" href="<?php echo(plugin_dir_url( __FILE__ )) ?>2048/css/jquery.2048.css">  
<script src="<?php echo(plugin_dir_url( __FILE__ )) ?>2048/js/jquery.2048.js"></script> 

 <a href="javascript:document.getElementById('2048_hints').scrollIntoView();"><b><?php _e( 'Scroll to Hints', 'simple-bitcoin-faucets' ); ?></b></a>

<hr>

<?php _e( 'Use Shortcode', 'simple-bitcoin-faucets' ); ?>  <code>[SBFG_2048]</code>
 <?php _e( 'where you want the game to appear', 'simple-bitcoin-faucets' ); ?> , 
 <?php _e( 'or', 'simple-bitcoin-faucets' ); ?>
 <a href="#" onclick="window.open(top.location.href+'&shortcode=[SBFG_2048]&name=2048');return false;"><?php _e('Generate test Page', 'simple-bitcoin-faucets' ); ?></a>

		<hr>
		
<div id="sfbg_2048_settings" style="float: left; padding:10px;">	

		<input sbf_game="2048" type="hidden" id="sbfg_sf_h_2048" name="sfbg_sf_2048" class="sbfg_sf_h_2048 sbfg_sf_h" value="<?php echo esc_attr( get_option('sfbg_sf_2048','') ); ?>"></input>
		<div sbf_game="2048" id="sbfg_sf_default_2048" style="display:none" class="sbfg_sf_default sbfg_sf_default_2048">64:123456,128:123456,256:123456,512:123456,1024:123456,2048:123456</div>
		<div sbf_game_settings="2048" style="display:none"></div> 
		<div id="sbfg_sf_table_2048" class="sbfg_sf_table" >
			<div id="sbfg_sf_table_header_2048" class="sbfg_sf_table_header">
				<div class="sbfg_sf_table_header_field"><?php _e( 'Score', 'simple-bitcoin-faucets' ); ?></div>
				<div class="sbfg_sf_table_header_field"><?php _e( 'Faucet Id', 'simple-bitcoin-faucets' ); ?></div>
			</div>

			<div sbf_game="2048" id="sbfg_sf_current_2048" class="sbfg_sf_current sbfg_sf_current_2048"></div>
		</div>
		<script>
			jQuery(document).ready(function() {
				sbf_sf_parse_from_options('2048',false,false,false);
			});
		</script>


		<?php submit_button(); ?>
</div>
<div id="sfbg_2048_example" style="float: left; padding:10px;">
<script>
<?php 
echo($this->g2048_shortcode_localize()); 
?>
</script>
<div class="sfbg_2048_game_wrap" style="width:400px;  border:0px dotted gray;"><center>
<div class="2048container text-center" id="sfbg_2048_game"></div>
<div id='sfbg_2048_faucet-TO-BE' style='display:none;width:400px;'></div></center></div>
<script src="<?php echo(plugin_dir_url( __FILE__ )) ?>2048/starter.js"></script>
</div>






<div id='2048_hints' style="clear:both;">
<hr>
<b><?php _e( 'Hints', 'simple-bitcoin-faucets' ); ?>:</b><br>
&nbsp;-&nbsp;
<a target=_new href="<?php _e('https://www.youtube.com/watch?v=-f5ckdopgag&list=PLRv0B44q8TR8bWrEwtMd6e17oW8wdRVIv&index=1', 'simple-bitcoin-faucets' ); ?>"><?php _e('Watch HowTo video', 'simple-bitcoin-faucets' ); ?></a>.
<hr>&nbsp;-&nbsp;
<?php _e('To play use your arrow keys to move the tiles, when two tiles with the same number touch, they merge into one', 'simple-bitcoin-faucets' ); ?>.
 <?php _e('You may want to put the instructions near the game', 'simple-bitcoin-faucets' ); ?>.
<hr>
&nbsp;-&nbsp;
<?php _e( 'Do not combine several games using keyboard input in the same page', 'simple-bitcoin-faucets' ); ?>
 <?php _e( '(like BlockRain and 2048)', 'simple-bitcoin-faucets' ); ?>.
<hr>
&nbsp;-&nbsp;
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