
jQuery(document).ready(function () {
//console.log('focus',jQuery( document.activeElement ));
		jQuery(document.activeElement).blur();
//console.log('focus2',jQuery( document.activeElement ));
        jQuery("#sfbg_2048_game").init2048();
});
	
	

sfbg_2048_show_reward = function(faucet_id)
{
	document.querySelector('#sfbg_2048_game').style.display = 'none';
	document.querySelector('#sfbg_2048_faucet-TO-BE').style.display = 'block';
	document.getElementById('sfbg_2048_faucet-TO-BE').id = 'wmexp-faucet-'+faucet_id;
	var script = document.createElement('script');
	script.src = 'https://wmexp.com/faucet/'+faucet_id+'/';
	document.head.appendChild(script);  	
}

