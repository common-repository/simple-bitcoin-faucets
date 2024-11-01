<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	die('no');
}
?>

<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap/3.3.4/css/bootstrap.min.css">
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<script>
		
		SBF_DB_do_add_bond = function(){
			
			var data = {
				action: 'SBF_DB_code_manage_action',
				B_COMMAND: 'ADD',
				B_PARAM: jQuery('.SBF_DB_dlg .SBF_DB_bond_amount_dlg').val(), //satoshi amount
				B_PARAM2: jQuery('.SBF_DB_dlg .SBF_DB_bond_block_dlg').val(), //bonds block name
				B_PARAM3: jQuery('.SBF_DB_dlg .SBF_DB_bond_quantity_dlg').val(), //bond quantity
			};
//console.log(data);	
			jQuery.post( '<?php echo(get_site_url()); ?>/wp-admin/admin-ajax.php', data, function(response) {
//				jQuery('#bonds_list_wrap').html(response + jQuery('#bonds_list_wrap').html());
//				jQuery('.SBF_DB_BOND_DIV').slideDown("fast",function(){jQuery('#SBF_DB_create_code').prop('disabled',false);});
//console.log(response);				
				bonds_update_list();				
			});		
			return false;
		}
		
		SBF_DB_do_delete_bond = function(bond_code){
			var conv_text1='<?php _e( 'Bond', 'simple-bitcoin-faucets' )  ?>';
			var conv_text2='<?php _e( 'is going to be permanently deleted!', 'simple-bitcoin-faucets' )  ?>';
			var conv_text3='<?php _e( 'Are you sure?', 'simple-bitcoin-faucets' )  ?>';
			if(!confirm(conv_text1 + " " + bond_code + " " + conv_text2 + "\n\n" + conv_text3 ))
			{
				return;
			}
			var data = {
				action: 'SBF_DB_code_manage_action',
				B_COMMAND: 'DELETE',
				B_PARAM: bond_code,
				B_PARAM2: '',
				B_PARAM3: '',				
			};		
			jQuery.post( '<?php echo(get_site_url()); ?>/wp-admin/admin-ajax.php', data, function(response) {
				jQuery('#' + response).slideUp("fast",function(){jQuery('#' + response).remove();});
		
			});			
		}




function bonds_update_list()
{
	jQuery('#bonds_list_wrap').html('<div class="sbfg_local_loader"></div>');
//json version	
	jQuery("body").css("cursor", "progress");
	var data = {
			action: 'SBF_DB_code_manage_action',
			B_COMMAND: 'FETCH_BONDS',
			B_PARAM: '',
			B_PARAM2: '',
			B_PARAM3: '',
		};

	var items_list;
	jQuery.post( '<?php echo(get_site_url()); ?>/wp-admin/admin-ajax.php', data, function(response) {
//console.log(response); 	
		items_list = JSON.parse(response);
//console.log('items_list',items_list); //exit;	
		if(! Array.isArray(items_list)){
			jQuery('#bonds_list_wrap').html(items_list);
		}else{ //got array
			if(items_list.length == 0){
				jQuery('#bonds_list_wrap').html('<h3><?php _e( 'You have no satoshi bonds yet. Create some!', 'simple-bitcoin-faucets' ); ?></h3>');
			}else{
				var list = SBF_DB_bonds_render_list(items_list);
				list = "<table class='bond_table table table-hover '>" + list + "</table>";
				jQuery('#bonds_list_wrap').html(list);
				SBF_DB_recalculate_all_blocks_values();
			}
		}
//console.log(items_list);
		setTimeout(function(){	jQuery('[data-toggle="tooltip"]').tooltip({title: "Hooray!", delay: {show: 1000, hide: 1000}});  },1000);
	});	
	jQuery("body").css("cursor", "default");
	
//	jQuery('#bonds_list_wrap').removeClass('sbfg_local_loader');
}


SBF_DB_recalculate_block_values = function(block_num){
	if(jQuery('#bond_block_' + block_num).css('display') == 'none'){ //invisible - just in case do nothing
		return;
	}
	
	jQuery('#bond_block_' + block_num).addClass('block_tpl_highlight');
	var block_bonds = jQuery('.bond_of_block_'+block_num).length;
	var block_redeemed = jQuery('.bond_redeemed.bond_of_block_'+block_num).length;
	jQuery('#bond_block_'+block_num+' .block_bonds_num').html(block_bonds);
	jQuery('#bond_block_'+block_num+' .block_redeemed_num').html(block_redeemed);
	if(block_bonds == 0){
		jQuery('#bond_block_' + block_num).addClass('block_tpl_highlight');
		jQuery('#bond_block_' + block_num + ' td').children().fadeOut(500,function(){
			jQuery('#bond_block_' + block_num).hide();
		});

	}else{
		jQuery('#bond_block_' + block_num).removeClass('block_tpl_highlight');
	}
	if(jQuery('#bond_block_' + block_num).next().css('display') !== 'none' ){
		jQuery('span.block_expander_'+block_num).html('&#8673;');
	}else{
		jQuery('span.block_expander_'+block_num).html('&#8675;');
	}

}

SBF_DB_recalculate_all_blocks_values = function(){
	for(var i = 0; i < 9999999; i++){
		if(jQuery('#bond_block_'+i).length){
			SBF_DB_recalculate_block_values(i);
		}else{
			return;
		}
	}
}

SBF_DB_expand_collaps_block = function(block_num,block_id){
console.log("SBF_DB_expand_collaps_block",block_num);
	jQuery('#bond_block_' + block_num).css("cursor", "progress");
	jQuery('body').css("cursor", "progress");
	jQuery('#bond_block_' + block_num).addClass('block_tpl_highlight');
	var local_storage_val = 'EXPANDED_' + block_id;
	if(jQuery('#bond_block_' + block_num).next().css('display') == 'none' ){
		setTimeout(function(){
console.log("DOWN START");		
			var counter = 0;
			jQuery('.bond_of_block_' + block_num).each(function(){	
				if(counter < 5){
					jQuery(this).slideDown(300);
				}else{
					jQuery(this).show();
				}
				counter++;
			})
			jQuery('#bond_block_' + block_num).removeClass('block_tpl_highlight');
			jQuery('#bond_block_' + block_num).css("cursor", "pointer");
			jQuery('body').css("cursor", "default");
			localStorage.setItem(local_storage_val, true);
console.log("DOWN END");		
		},10);
	}else{
		setTimeout(function(){	
console.log("UP START");		
			var counter = 0;
			jQuery('.bond_of_block_' + block_num).each(function(){
				if(counter < 5){
					jQuery(this).slideUp(300);
				}else{
					jQuery(this).hide();
				}
				counter++;
			})
//			jQuery('.bond_of_block_' + block_num).hide(function(){
//			});
			jQuery('#bond_block_' + block_num).removeClass('block_tpl_highlight');
			jQuery('#bond_block_' + block_num).css("cursor", "pointer");
			jQuery('body').css("cursor", "default");
			localStorage.setItem(local_storage_val, false);
console.log("UP END");			
		},10);	
	}
}

SBF_DB_block_notify_switch  = function(block_id){
//console.log(block_id);
	SBF_DB_cancelBubble();
	var check_id = 'block_notify_' + block_id;
	var checkBox = document.getElementById(check_id);
	var new_state = 0;
	if (checkBox.checked == true){
		new_state = 1;
	}

	jQuery("body").css("cursor", "progress");
	jQuery('#'+check_id).fadeOut(300);

	
	var data = {
		action: 'SBF_DB_code_manage_action',
		B_COMMAND: 'CHANGE_NOTIFY_BOND_BLOCK',
		B_PARAM: block_id,
		B_PARAM2: new_state,
		B_PARAM3: '',				
	};		
	jQuery.post( '<?php echo(get_site_url()); ?>/wp-admin/admin-ajax.php', data, function(response) { 
		if(response.indexOf(' ') != -1){
			alert(response); //error message
		}else{
		}
		jQuery('#'+check_id).fadeIn(500);
		jQuery("body").css("cursor", "default");
	});		  
  
}

SBF_DB_delete_block = function(block_num,block_id){
//console.log('SBF_DB_delete_block',block_num,block_name);
	SBF_DB_cancelBubble();
	jQuery('#bond_block_' + block_num).addClass('bond_tpl_highlight');
	if(1){ //always ask
		var conf_text = '';
		conf_text += '<?php _e( 'You are about to delete the all bonds in this block!', 'simple-bitcoin-faucets' ); ?>';
		conf_text += "\n";
		conf_text += '<?php _e( 'Make sure you did not give away any the bond codes.', 'simple-bitcoin-faucets' ); ?>';
		conf_text += "\n";
		conf_text += '<?php _e( 'All bond will be permanently voided and can not be exchanged for satoshi.', 'simple-bitcoin-faucets' ); ?>';
		conf_text += "\n\n";		
		conf_text += '<?php _e( 'DELETE ALL BONDS OF THIS BLOCK?' , 'simple-bitcoin-faucets' ); ?>';		
		if(!confirm(conf_text)){
			jQuery('#bond_block_' + block_num).removeClass('bond_tpl_highlight');
			return;
		}
	}
	
	jQuery("body").css("cursor", "progress");
	jQuery('#bond_block_' + block_num + ' td').children().fadeOut(500);

	
	var data = {
		action: 'SBF_DB_code_manage_action',
		B_COMMAND: 'DELETE_BOND_BLOCK',
		B_PARAM: block_id,
		B_PARAM2: block_num,
		B_PARAM3: '',				
	};		
	jQuery.post( '<?php echo(get_site_url()); ?>/wp-admin/admin-ajax.php', data, function(response) { 
		if(response.indexOf(' ') != -1){
			alert(response); //error message
		}else{
			jQuery('.bond_of_block_' + block_num).each(function(){
				jQuery(this).remove();
			});
			SBF_DB_recalculate_block_values(block_num);
		}
		jQuery("body").css("cursor", "default");
	});		

}

SBF_DB_delete_bond = function(bond_code,redeem_date,block_num){
	jQuery('.' + bond_code).addClass('bond_tpl_highlight');	
	if(redeem_date == '<?php _e( 'no', 'simple-bitcoin-faucets' ); ?>'){ //not redeed yet, gotta ask
		var conf_text = '';
		conf_text += '<?php _e( 'You are about to delete the unredeemed bond!', 'simple-bitcoin-faucets' ); ?>';
		conf_text += "\n";
		conf_text += '<?php _e( 'Make sure you did not give away the bond code.', 'simple-bitcoin-faucets' ); ?>';
		conf_text += "\n";
		conf_text += '<?php _e( 'Bond will be permanently voided and can not be exchanged for satoshi.', 'simple-bitcoin-faucets' ); ?>';
		conf_text += "\n";		
		conf_text += '<?php _e( 'Delete the bond?' , 'simple-bitcoin-faucets' ); ?>';		
		if(!confirm(conf_text)){
			jQuery('.' + bond_code).removeClass('bond_tpl_highlight');
			return;
		}
	}
	
	jQuery("body").css("cursor", "progress");
	jQuery('.' + bond_code + ' td').children().fadeOut(500);

	
	var data = {
		action: 'SBF_DB_code_manage_action',
		B_COMMAND: 'DELETE_BOND',
		B_PARAM: bond_code,
		B_PARAM2: '',
		B_PARAM3: '',				
	};		
	jQuery.post( '<?php echo(get_site_url()); ?>/wp-admin/admin-ajax.php', data, function(response) { //returns bondcode
		if(response.indexOf(' ') != -1){
			alert(response); //error message
		}else{
			jQuery('.' + response).remove();
			SBF_DB_recalculate_block_values(block_num);
		}
		jQuery("body").css("cursor", "default");
	});		
}

SBF_DB_bonds_render_list = function(a) { //a - array of items_list
	var block_tpl = jQuery('#block_tpl_wrap').html();
//console.log(block_tpl);		
	var bond_tpl = jQuery('#bond_tpl_wrap').html();
//console.log(bond_tpl);		
	var ret = '';
	var current_block = '';//if you call your block like this - you are in idiot
	var block_count = -1; //so we start with 0 after increment
	for(var i = 0; i < a.length; i++) {
//console.log(a[i]);	
		var a_code = a[i]['code'];
		var a_amount = a[i]['amount'];
		var a_block = a[i]['block'];
		var a_redeemed_to = a[i]['to'];
		var a_redeemed_ip = a[i]['IP'];
		var a_bond_id = a[i]['bond_id'];
		var a_block_id = a[i]['block_id'];
		var a_block_owner = a[i]['block_owner'];
		var a_block_notify_redeem = a[i]['block_notify_redeem'];
		var a_block_locked = a[i]['block_locked'];
		if(!a_block || a_block.length == 0)
			a_block = '<?php _e( 'BLOCK NAME NOT SET', 'simple-bitcoin-faucets' ); ?>';
		var a_created = a[i]['created'];
		if(a_created.indexOf('-') == -1){	
			var d = new Date(a_created * 1000);
			a_created = ("0" + d.getDate()).slice(-2) + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + d.getFullYear() + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
		}else{//came as date string
		}
		var a_redeemed = a[i]['redeemed'];
		if( (!a_redeemed) || (a_redeemed == '0000-00-00 00:00:00' ) ){
			a_redeemed = '<?php _e( 'no', 'simple-bitcoin-faucets' ); ?>';
		}else{
			if(a_redeemed.indexOf('-') == -1){			
				d = new Date(a_redeemed * 1000);
				a_redeemed = ("0" + d.getDate()).slice(-2) + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + d.getFullYear() + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
			}else{//came as date string
			}
//			a_redeemed = '<a href="javascript:jQuery(`#rd'+i+ '`).show()" >'+a_redeemed + '</a><div style="display:none;" id="rd'+i+'">' + a_redeemed_to + '<br>' + a_redeemed_ip + '</div>';
			a_redeemed = a_redeemed + '<div>' + a_redeemed_to + '<br>' + a_redeemed_ip + '</div>';
		}
					
//console.log(current_block,a_block);		
		if(current_block !== a_block){ //new block
			block_count++;
			var block_box = block_tpl;
//if(block_count  == 1) console.log(block_box);				
			block_box = block_box.replace(/BLOCK_NAME_X/g,a_block);
			block_box = block_box.replace(/BLOCK_NUM_X/g,block_count); 
			block_box = block_box.replace(/BLOCK_ID/g,a_block_id); 
			if(a_block_notify_redeem == '1'){
				block_box = block_box.replace(/class="block_notify"/g,'class="block_notify" checked="checked" ');
			}
//if(block_count  == 1) console.error(block_box);	
	
			ret += block_box;
			current_block = a_block;
		}
		var bond_box = bond_tpl;	
		var local_storage_val = 'EXPANDED_' + a_block_id;
		var bond_expanded = localStorage.getItem(local_storage_val);
//console.log(local_storage_val,bond_expanded,bond_expanded === 'true');	
		if(bond_expanded === 'true'){
			bond_box = bond_box.replace('display:none;','');
		}

		bond_box = bond_box.replace(/BOND_CODE_X/g,a_code);
		bond_box = bond_box.replace(/BOND_SATOSHI/g,a_amount);
		bond_box = bond_box.replace(/BOND_CREATED_DATE/g,a_created)
		bond_box = bond_box.replace(/BOND_REDEEMED_DATE/g,a_redeemed)
		bond_box = bond_box.replace(/BLOCK_CLASS_X/g, block_count);
		bond_box = bond_box.replace(/BLOCK_CLASS_N/g, current_block);
		bond_box = bond_box.replace('bond_tpl','bond_tpl ' + a_code); //only once , we have code as class now 
		bond_box = bond_box.replace(/BOND_NUM_X/g,i);
		bond_box = bond_box.replace(/BOND_ID_X/g,a_bond_id);
		bond_box = bond_box.replace(/BLOCK_ID_X/g,a_block_id);
		if(a_redeemed != '<?php _e( 'no', 'simple-bitcoin-faucets' ); ?>'){
			bond_box = bond_box.replace('bond_tpl','bond_tpl bond_redeemed'); //only once! 
		}
		ret += bond_box;

//		ret += a_code + "<br>\n";
	}
	ret = ret.replace(/<tr1/g, '<tr');
	ret = ret.replace(/<td1/g, '<td');
	return ret;
}

SBF_DB_cancelBubble = function(block_num){
    var e = window.event;
     if (e.stopPropagation) {//IE9 & Other Browsers
      e.stopPropagation();
    }else {//IE8 and Lower
      e.cancelBubble = true;
    }
}

SBF_DB_harvest_block = function(block_num){
	SBF_DB_cancelBubble();

	var current_bond_codes = '';
	jQuery('.bond_of_block_' + block_num).each(function(){
		var bond_code = jQuery(this).attr('bond_code')
//console.log('SBF_DB_harvest_block',bond_code);			
		current_bond_codes += bond_code + "\n";
	});
	current_bond_codes = current_bond_codes.substring(0, current_bond_codes.length - 1); //last \n
//alert(current_bond_codes);
//	SBF_DB_current_bond_codes
	jQuery('#SBF_DB_current_bond_codes').html(current_bond_codes);
	jQuery(".SBF_DB_bond_codes").val(jQuery('#SBF_DB_current_bond_codes').html());
	jQuery('#SBF_DB_block_name').html('<?php _e( 'Bond Block', 'simple-bitcoin-faucets' ); ?>:<b>' + jQuery('.block_name_'+block_num).html()+'</b>');
	
	SBF_DB_bonds_make_codes_list(block_num);
}

SBF_DB_apply_prefix_change = function(prefix_edit,codes_textarea) {
console.log("1 prefix_edit,codes_textarea",prefix_edit,codes_textarea);
	if( typeof prefix_edit === 'undefined'){
		prefix_edit = jQuery('.SBF_DB_DIALOG_bond_prefix');
		prefix_edit.css('border','1px solid red');
		
	}
	if( typeof codes_textarea === 'undefined'){
		codes_textarea = jQuery('.SBF_DB_DIALOG_bond_codes');
		codes_textarea.css('border','1px solid green');
	}	

	var prefix = prefix_edit.val();
console.log("2 prefix_edit,codes_textarea,val",prefix_edit,codes_textarea,prefix);		
	if( prefix === 'undefined'){
		prefix = '';
	}
//alert(prefix)	
	localStorage.setItem('CURRENT_PREFIX',prefix);
//console.log(prefix);
	var current_bonds =  jQuery('#SBF_DB_current_bond_codes').html();
//console.log(current_bonds);
	var prefixed_codes = prefix + current_bonds.replace(/\n/g, "\n"+prefix);
	codes_textarea.val(prefixed_codes);
	
}

SBF_DB_bonds_make_codes_list = function(block_num) {
  // this initializes the dialog (and uses some common options that I do)
//  jQuery("#bonds_blocks_list_dialog").dialog({autoOpen : false, modal : true, show : "blind", hide : "blind"});

  // next add the onclick handler
//   jQuery("#bonds_blocks_list_dialog").dialog("open");
	jQuery(".SBF_DB_bond_codes").val(jQuery('#SBF_DB_current_bond_codes').html());
	var o_box = jQuery('#bonds_blocks_list_dialog').clone(true, true);
	
	var current_prefix = localStorage.getItem('CURRENT_PREFIX');

	if(current_prefix === 'undefined'){
		current_prefix = '';
	}

	if (typeof forced_redeem_url !== 'undefined') {
		current_prefix = forced_redeem_url;
		jQuery(o_box).find('.SBF_DB_bond_prefix').prop( "disabled", true );
		jQuery(o_box).find('#SBF_DB_prefix_wrap').hide();
	}
	
	if(current_prefix){
		jQuery(o_box).find('.SBF_DB_bond_prefix').val(current_prefix);
	}
	
	jQuery(o_box).find('.SBF_DB_bond_codes').addClass('SBF_DB_DIALOG_bond_codes').select();
	jQuery(o_box).find('.SBF_DB_bond_prefix').addClass('SBF_DB_DIALOG_bond_prefix').focus();
	SBF_DB_apply_prefix_change(jQuery(o_box).find('.SBF_DB_bond_prefix'), jQuery(o_box).find('.SBF_DB_bond_codes'));
	
	jQuery(o_box).find('.SBF_DB_bond_prefix').on('change keyup paste focusout focusin', function (e) {
//console.log(e);	
		SBF_DB_apply_prefix_change();
	});
	
	jQuery.MessageBox({
		title : "<?php _e( 'Bond Codes', 'simple-bitcoin-faucets' ); ?>",
//		message : "<?php _e( 'Bond Codes', 'simple-bitcoin-faucets' ); ?>",
		input   : o_box,
		top     : "auto"
	}).then(function(){
//console.log('then');
	}).done(function(data){
//		console.log(data);
//		localStorage.setItem('CURRENT_PREFIX',data[0]);
		jQuery(o_box).find('.SBF_DB_bond_prefix').off('change keyup paste focusout focusin');
		o_box.remove();
	});

jQuery(document).ready(function(){
  //jQuery('[data-toggle="tooltip"]').tooltip();  
});	
	
//jQuery.MessageBox("A plain MessageBox can replace Javascript's window.alert(), and it looks definitely better...");
}
</script>		

<style>
#bonds_list_wrap,#bonds_top_list_wrap{
min-height:20px;
width:98%;
border: 0px dotted gray;
}

.bad_input{
    border-color: #a94442;
   -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
}
.good_input{
	border-color:#3c763d;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
}


.bond_table{
	height: auto !important;
	width:100%;
	border:1px solid black;
	border-collapse: collapse;
	padding: 0px;
	font-size: 12px;
}
.bond_table .bond_tpl{
	/*display:none; */
}
.bond_table .block_tpl{
	font-size:120%;
/*	background-color: lightgray; */
	white-space: nowrap;
	text-overflow: ellipsis;
	overflow: hidden;
	cursor: pointer;
}
.bond_table .block_tpl td.block_name{
	white-space: nowrap;
	text-overflow: ellipsis;
	overflow: hidden;
	max-width: 100px;	
	font-size:150%;
}
.bond_table .block_tpl td.block_total_num{
	max-width: 500px;	
}
.bond_table .block_tpl_highlight{
	background-color: lightgreen;
	color: white;
}
.bond_table .bond_tpl_highlight{
	background-color: lightgreen;
	color: white;
}
.block_harvest{
	width:1%;
}
.bond_table .bond_tpl:nth-child(even) {
/*  background-color: #f2f2f2; */
}
#bonds_create_new{
	border:1px solid black;
    border-collapse: separate;
    border-spacing: 2px;
 
}
#bonds_create_new tr{
	background-color: lightgray;
}
#SBF_DB_block_name{
	white-space: nowrap;
	text-overflow: ellipsis;
	overflow: hidden;
	max-width: 300px;	
}


</style>
<div id='block_tpl_wrap' style='display:none;'> 
	<tr1 class='block_tpl info ' id='bond_block_BLOCK_NUM_X' onClick="SBF_DB_expand_collaps_block(BLOCK_NUM_X,BLOCK_ID)">
		<td1 class='block_harvest'>
			<button type="button" data-delay="1000" data-toggle="tooltip" class='SBF_DB_tooltip block_harvest_button btn btn-primary' onclick="SBF_DB_harvest_block(BLOCK_NUM_X);" title='<?php _e( 'Get unredeemed Bond Codes for the block in convenient format', 'simple-bitcoin-faucets' ); ?>'><span class="glyphicon glyphicon-export"></span>&nbsp;<?php _e( 'Export', 'simple-bitcoin-faucets' ); ?></button>
		</td1>
		<td1 class='block_name' colspan=2>
			<span class='block_name block_name_BLOCK_NUM_X' data-toggle="tooltip"  title="BLOCK_NAME_X">BLOCK_NAME_X</span>
		</td1>
		<td1 class='block_total_num' ><?php _e( 'bonds', 'simple-bitcoin-faucets' ); ?>: <span class='block_bonds_num'>0</span></td1>
		<td1 class='block_redeemed'><?php _e( 'redeemed', 'simple-bitcoin-faucets' ); ?>: <span class='block_redeemed_num'>0</span></td1>
		<td1 class='block_notify'>
		<div class='block_notify' style='float:left;display:inline;' data-toggle="tooltip"  title='<?php _e( 'Notify by email when bond of this block is redeemed', 'simple-bitcoin-faucets' ); ?>' >
			<input type="checkbox" id='block_notify_BLOCK_ID' class='block_notify' onclick="SBF_DB_block_notify_switch(BLOCK_ID)"  ></input><br><span class="glyphicon glyphicon-envelope"></span>
		</div>
		</td1>
		<td1 class='block_control'>
		<button type="button" data-toggle="tooltip" class='block_delete btn btn-danger' style='float:right' onclick="SBF_DB_delete_block(BLOCK_NUM_X,BLOCK_ID)" title='<?php _e( 'Delete all bonds of the block', 'simple-bitcoin-faucets' ); ?>'><span class="glyphicon glyphicon-remove"></span>&nbsp;<?php _e( 'Delete', 'simple-bitcoin-faucets' ); ?></button>
		</td1>
	</tr1>
</div>

<div id='bond_tpl_wrap' style='display:none;'>
	<tr1 class='bond_tpl bond_of_block_BLOCK_CLASS_X' id='bond_BOND_NUM_X'  style='display:none;' parent_block='BLOCK_CLASS_X' parent_block_name='BLOCK_CLASS_N' bond_code='BOND_CODE_X' block_id='BLOCK_ID'>
		<td1>&nbsp;</td1>
		<td1><span><?php _e( 'Bond code', 'simple-bitcoin-faucets' ); ?>:</span> <span class='bond_code'>BOND_CODE_X</span></td1>
		<td1><span><?php _e( 'satoshi', 'simple-bitcoin-faucets' ); ?>:</span> <span class='bond_amount'>BOND_SATOSHI</span></td1>
		<td1><span><?php _e( 'created', 'simple-bitcoin-faucets' ); ?>:</span> <span class='bobd_created_date'>BOND_CREATED_DATE</span></td1>
		<td1><span><?php _e( 'redeemed', 'simple-bitcoin-faucets' ); ?>:</span> <span class='bond_redeemed_date'>BOND_REDEEMED_DATE</span></td1>
		<td1></td1>
		<td1 align='right'><button data-toggle="tooltip" type="button" class='bond_delete btn btn-danger' onclick="SBF_DB_delete_bond('BOND_CODE_X','BOND_REDEEMED_DATE',BLOCK_CLASS_X)" title='<?php _e( 'Delete this bond', 'simple-bitcoin-faucets' ); ?>' ><span class="glyphicon glyphicon-remove"></span></button></td1>
	</tr1>
</div>

<div style='display:none;'>

<div id='SBF_DB_current_bond_codes' ></div>

<div id="bonds_blocks_list_dialog" style="min-width:500px;" >
  <div id="bonds_blocks_list_dialog_inner" >
	<div id='SBF_DB_block_name'>1234</div>
	<span id='SBF_DB_prefix_wrap'>
		<?php _e( 'Prefix', 'simple-bitcoin-faucets' ); ?> : <input class='SBF_DB_bond_prefix' value= '' placeholder='<?php echo(get_site_url())?>/<REDEEM_PAGE>/?bond=>' maxlength=200 size=40 type='text'></input> 
	</span>
	<textarea class='SBF_DB_bond_codes' rows='10' style='width:100%;overflow: auto; white-space:pre;' readonly>	</textarea>
	</input> 
  </div>
</div>

</div>

