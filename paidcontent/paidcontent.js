//
//
//


var sbf_paidcontent_popup;
sbf_paidcontent_deposit = function(content_id,satosh_amount,valid_seconds,allow_edit){
//alert(content_id +"\n"+satosh_amount +"\n"+valid_seconds +"\n"+allow_edit); return false;
	if(sbf_paidcontent_popup)
	{
		sbf_paidcontent_popup.close();
	}
	url = '/?CRYPTOOMEPAYFORCONTENT=' + content_id + '&VALID_SECONDS=' + valid_seconds;
	if(!allow_edit)
	{
		url = url + '&SATOSHI_AMOUNT=' + satosh_amount;
		sbf_paidcontent_popup = window.open(url,'pay_for_content_popup');
		return;
	}
	
	jQuery('body').on('click', '.messagebox_button_done', function(){
		sbf_paidcontent_popup = window.open(url,'pay_for_content_popup');
	});
	jQuery.MessageBox({
		input    : '' + satosh_amount,
		message  : paidcontent_text_enter_amount,
		buttonFail  : paidcontent_text_cancel,
	}).done(function(data){
		jQuery('body').off('click', '.messagebox_button_done');
	    var val = parseInt(0+jQuery.trim(data));
		if ( (val <= 0) ) 
		{
			val = satosh_amount;
		}
		url = url + '&SATOSHI_AMOUNT=' + val;
		sbf_paidcontent_popup = window.open(url,'pay_for_content_popup');		
	})
	.fail(function(){
		jQuery('body').off('click', '.messagebox_button_done');
	});
}//sbf_paidcontent_deposit