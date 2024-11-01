<?php
?>
<script>
<?php echo($this->main_js_shortcode_localize()); ?>
</script>
<?php echo($this->minesweeper_shortcode_localize()); ?> 
<?php echo($this->minesweeper_shortcode_top()) ?> 

 <a href="javascript:document.getElementById('minesweeper_hints').scrollIntoView();"><b><?php _e( 'Scroll to Hints', 'simple-bitcoin-faucets' ); ?></b></a>

<hr>

<?php _e( 'Use Shortcode', 'simple-bitcoin-faucets' ); ?>  <code>[SBFG_MINESWEEPER]</code>
 <?php _e( 'where you want the game to appear', 'simple-bitcoin-faucets' ); ?> , 
 <?php _e( 'or', 'simple-bitcoin-faucets' ); ?>
 <a href="#" onclick="window.open(top.location.href+'&shortcode=[SBFG_MINESWEEPER]&name=Minesweeper');return false;"><?php _e('Generate test Page', 'simple-bitcoin-faucets' ); ?></a>

		<hr>
		
<div id="sfbg_minesweeper_settings" style="float: left; padding:10px;">	

		<input sbf_game="minesweeper" type="hidden" id="sbfg_sf_h_minesweeper" name="sfbg_sf_minesweeper" class="sbfg_sf_h_minesweeper sbfg_sf_h" value="<?php echo esc_attr( get_option('sfbg_sf_minesweeper','') ); ?>"></input>
		<div sbf_game="minesweeper" id="sbfg_sf_default_minesweeper" style="display:none" class="sbfg_sf_default sbfg_sf_default_minesweeper">beginner:123456,intermediate:123456,expert:123456</div>
		<div sbf_game_settings="minesweeper" style="display:none"></div> 
		<div id="sbfg_sf_table_minesweeper" class="sbfg_sf_table" >
			<div id="sbfg_sf_table_header_minesweeper" class="sbfg_sf_table_header">
				<div class="sbfg_sf_table_header_field"><?php _e( 'Score', 'simple-bitcoin-faucets' ); ?></div>
				<div class="sbfg_sf_table_header_field"><?php _e( 'Faucet Id', 'simple-bitcoin-faucets' ); ?></div>
			</div>

			<div sbf_game="minesweeper" id="sbfg_sf_current_minesweeper" class="sbfg_sf_current sbfg_sf_current_minesweeper"></div>
		</div>

		
		<script>
			jQuery(document).ready(function() {
				sbf_sf_parse_from_options('minesweeper',false,false,false);
			});
		</script>


		<?php submit_button(); ?>
</div>
<div id="sfbg_minesweeper_example" style="float: left; padding:10px;">
<script>
</script>

<?php echo($this->minesweeper_shortcode_body()) ?> 


</div>






<div id='minesweeper_hints' style="clear:both;">
<hr>
<b><?php _e( 'Hints', 'simple-bitcoin-faucets' ); ?>:</b><br>
&nbsp;-&nbsp;
<a target=_new href="<?php _e('https://www.youtube.com/watch?v=-f5ckdopgag&list=PLRv0B44q8TR8bWrEwtMd6e17oW8wdRVIv&index=1', 'simple-bitcoin-faucets' ); ?>"><?php _e('Watch HowTo video', 'simple-bitcoin-faucets' ); ?></a>.
<hr>&nbsp;-&nbsp;
<?php _e( 'You can use this code to force visitors to reload the page for new game', 'simple-bitcoin-faucets' ); ?>:<code><br>[SBFG_MINESWEEPER]
<br>&lt;script&gt;
<br>&nbsp; &nbsp; &nbsp; document.getElementById('sbfg_ms_newgame').style.display='none';
<br>&lt;/script&gt;
</code>
<hr>
</div>

 
<script>

</script>		
<?php

?>