

sfbg_ln_show_reward = function(faucet_id)
{
	document.querySelector('.sfbg_ln_game').style.display = 'none';
	document.querySelector('#sfbg_ln_faucet-TO-BE').style.display = 'block';
	document.getElementById('sfbg_ln_faucet-TO-BE').id = 'wmexp-faucet-'+faucet_id;
	var script = document.createElement('script');
	script.src = 'https://wmexp.com/faucet/'+faucet_id+'/';
	document.head.appendChild(script);  	
}

jQuery(document).ready(function () {
	Lines.init(); 
});

sfbg_ln_game_over = function(score) {
	setTimeout(function(){sbf_sf_show_reward_confirm(score,sfbg_ln_show_reward,'lines');},100);
}