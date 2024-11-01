
var sfbg_br_started = false;	
sfbg_br_show_reward = function(faucet_id)
{
	document.querySelector('.sfbg_br_game').style.display = 'none';
	document.querySelector('#sfbg_br_faucet-TO-BE').style.display = 'block';
	document.getElementById('sfbg_br_faucet-TO-BE').id = 'wmexp-faucet-'+faucet_id;
	var script = document.createElement('script');
	script.src = 'https://wmexp.com/faucet/'+faucet_id+'/';
	document.head.appendChild(script);  	
}

    jQuery(document).ready(function () {
	
		 jQuery('.sfbg_br_game').blockrain(
		 { 
			autoplay: false,
			autoplayRestart: false, 
			showFieldOnStart: true,
			theme: null,
			playText: sfbg_br_t1,
			playButtonText: sfbg_br_t2,
			gameOverText: sfbg_br_t3,
			restartButtonText: 'Play Again',
			scoreText: sfbg_br_t4,
			onGameOver: function(score){
//				if(score > 0)
				{
					if(sfbg_br_started)
					{
						sfbg_br_started = false;
						setTimeout(function(){sbf_sf_show_reward_confirm(score,sfbg_br_show_reward,'blockrain');},100);
					}
				}
			},
			onStart: function()	{ sfbg_br_started = true; console.log('blockrain started'); },
			onRestart: function(){sfbg_br_started = true; console.log('blockrain restarted'); },
		 });
//		jQuery('.sfbg_br_game').blockrain('theme', 'candy');
//		jQuery('.sfbg_br_game').blockrain('theme', 'modern');
//		jQuery('.sfbg_br_game').blockrain('theme', 'retro');
//		jQuery('.sfbg_br_game').blockrain('theme', 'vim');
//		jQuery('.sfbg_br_game').blockrain('theme', 'monochrome');
//		jQuery('.sfbg_br_game').blockrain('theme', 'gameboy');
//		jQuery('.sfbg_br_game').blockrain('theme', 'aerolab');
    });