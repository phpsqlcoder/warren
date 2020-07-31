function get_notifications(){
	//alert();
	//console.log('xxx');
	$.ajax({
		method: "POST",
		url: $('#hidden_url').val()+"ajax.php?act=notifications_show"
	})
	.done(function( d ) {
		
		n = jQuery.parseJSON(d);
		//console.log(n.qqq);
		$('#header_notification_bar').html(n.notification);
		//alert(n.qqq);
		if(n.has_noti == 1){
			toastr.info('<div><a href="#" onclick="toggle_comments('+n.id+')">'+n.noti_msg+' from '+n.noti_from+'</a></div>');
		}		

	});	
}

function toggle_comments(id){	
	$('body').toggleClass('page-quick-sidebar-open');
	$('#msg_contents').html('');
	get_chat_area(id);
	get_comments(id);
}

window.onload = function(e){    
	$('.dropdown-quick-sidebar-toggler').click(function (e) {	
		$('body').toggleClass('page-quick-sidebar-open');	
		$('#msg_contents').html('');
		get_chat_area(this.id);
		get_comments(this.id);
	}); 
	setInterval(get_notifications, 8000);
	
}


