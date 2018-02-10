
function makeList() {
    var fo_obj = xGetElementById("mobilemessage_fo");
    var mobilemessage_srl = new Array();

    if(typeof(fo_obj.cart.length)=='undefined') {
        if(fo_obj.cart.checked) mobilemessage_srl[mobilemessage_srl.length] = fo_obj.cart.value;
    } else {
        var length = fo_obj.cart.length;
        for(var i=0;i<length;i++) {
            if(fo_obj.cart[i].checked) mobilemessage_srl[mobilemessage_srl.length] = fo_obj.cart[i].value;
        }
    }

    return mobilemessage_srl;
}

/* 일괄 취소 */
function cancelMobilemessage() {
    var message_id = makeList();

    if(message_id.length<1) return;

    var url = './?module=textmessage&act=dispTextmessageAdminCancelReserv&message_id='+message_id.join(',');
    winopen(url, 'delete_log','scrollbars=no,width=400,height=500,toolbars=no');
}

/* 일괄 취소 후 */
function completeMobilemessageCancel(ret_obj) {
    alert(ret_obj['message']);
    opener.location.href = opener.current_url;
    window.close();
}

/* 일괄 취소(그룹) */
function cancelGroupMessages() {
    var group_ids = makeList();

    if(group_ids.length<1) return;

    var url = './?module=textmessage&act=dispTextmessageAdminCancelGroup&group_ids='+group_ids.join(',');
    winopen(url, 'delete_log','scrollbars=no,width=400,height=500,toolbars=no');
}

/* 일괄 취소 후(그룹) */
function completeCancelGroupMessages(ret_obj) {
    alert(ret_obj['message']);
    opener.location.href = opener.current_url;
    window.close();
}

jQuery(function($) {
	$('a.modalAnchor.cancelGroup').bind('before-open.mw', function(event){
		var group_ids = makeList();
		if(group_ids.length<1) return;

		exec_xml(
			'textmessage',
			'getTextmessageAdminCancelGroup',
			{group_ids:group_ids},
			function(ret){
				var tpl = ret.tpl.replace(/<enter>/g, '\n');
				$('#cancelForm').html(tpl);
			},
			['error','message','tpl']
		);

	});
	$('a.modalAnchor.cancelReserv').bind('before-open.mw', function(event){
		var message_id = makeList();
		if(message_id.length<1) return;

		exec_xml(
			'textmessage',
			'getTextmessageAdminCancelReserv',
			{message_id:message_id},
			function(ret){
				var tpl = ret.tpl.replace(/<enter>/g, '\n');
				$('#cancelForm').html(tpl);
			},
			['error','message','tpl']
		);

	});
	$('a.modalAnchor.deleteGroup').bind('before-open.mw', function(event){
		var group_ids = makeList();
		if(group_ids.length<1) return;

		exec_xml(
			'textmessage',
			'getTextmessageAdminDeleteGroup',
			{group_ids:group_ids},
			function(ret){
				var tpl = ret.tpl.replace(/<enter>/g, '\n');
				$('#deleteForm').html(tpl);
			},
			['error','message','tpl']
		);

	});
	$('a.modalAnchor.deleteMessages').bind('before-open.mw', function(event){
		var message_ids = makeList();
		if(message_ids.length<1) return;

		exec_xml(
			'textmessage',
			'getTextmessageAdminDeleteMessages',
			{message_ids:message_ids},
			function(ret){
				var tpl = ret.tpl.replace(/<enter>/g, '\n');
				$('#deleteForm').html(tpl);
			},
			['error','message','tpl']
		);
	});
});
