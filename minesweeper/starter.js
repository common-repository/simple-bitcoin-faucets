
jQuery(document).ready(function () {
        // set a global instance of Minesweeper
        minesweeper = new MineSweeper();
        // init the (first) game
        minesweeper.init();
});
	
	

sfbg_ms_show_reward = function(faucet_id)
{
	document.querySelector('#sfbg_minesweeper_game').style.display = 'none';
	document.querySelector('#sfbg_minesweeper_faucet-TO-BE').style.display = 'block';
	document.getElementById('sfbg_minesweeper_faucet-TO-BE').id = 'wmexp-faucet-'+faucet_id;
	var script = document.createElement('script');
	script.src = 'https://wmexp.com/faucet/'+faucet_id+'/';
	document.head.appendChild(script);  	
}

	