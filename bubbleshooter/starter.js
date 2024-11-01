

sfbg_bubbleshooter_show_reward = function(faucet_id)
{
	document.querySelector('.sfbg_bubbleshooter_game').style.display = 'none';
	document.querySelector('#sfbg_bubbleshooter_faucet-TO-BE').style.display = 'block';
	document.getElementById('sfbg_bubbleshooter_faucet-TO-BE').id = 'wmexp-faucet-'+faucet_id;
	var script = document.createElement('script');
	script.src = 'https://wmexp.com/faucet/'+faucet_id+'/';
	document.head.appendChild(script);  	
}

jQuery(document).ready(function () {
	bubbleshooter_window_onload(); 
});

sfbg_bubbleshooter_game_over = function(score) {
	setTimeout(function(){sbf_sf_show_reward_confirm(score,sfbg_bubbleshooter_show_reward,'bubbleshooter');},100);
}