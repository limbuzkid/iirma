(function ($) {
	$('a.approvejobs').click(function(event){
		event.preventDefault();
		if($(this).attr('data-status')==1){
			var text = "approve";
			var text2 = "approved";
		} else {
			var text = "decline";
			var text2 = "declined";
		}
		var proceed = confirm('Are you sure to '+text);
		if(proceed){
			$.ajax({
		      url: "/admin/iirmalisting/job-approve-decline",
		      type:'POST',
		      dataType: "json",
		      data: {
		        'nodeid':$(this).attr('data-nodeid'),
		        'nodestatus':$(this).attr('data-status')
		      },
		      success: function(response) {
		        if(response.msg == 'success') {
		           alert('The job post has been '+text2);
		           $('#node-'+response.nodeid).hide();
		        } else {
		            
		        }
		      }
		    });
		}
	});
})(jQuery);