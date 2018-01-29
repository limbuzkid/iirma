(function ($) {
 
  	size_li = $("#jobsList li").size();
	x=6;
	if(x>=size_li){
	  $('.jobsLoadMore').hide();
	}
	y = x-1;
	$('#jobsList li:lt('+x+')').attr('style','display:inline-block');
	$('#jobsList li:gt('+y+')').attr('style','display:none');
	$('.jobsLoadMore').click(function () {
	    x= (x+6 <= size_li) ? x+6 : size_li;
	    $('#jobsList li:lt('+x+')').attr('style','display:inline-block');
	    if(x==size_li){
	      $(this).hide();
	    }
	});

	$('.applyirmaprofile,.uploadresumeclick').click(function(event) {
        $('.errorMsg').remove();
	    event.preventDefault();
	    var fd = new FormData();
	    if($(this).hasClass("applyirmaprofile")){
	    	$('#uploadresume #nodeid').val($(this).attr('data-nodeid'));
	    	fd.append('irma_profile',1);
	    } else {
	    	fd.append('irma_profile',0);
	    }
    	fd.append('nodeid', $('input#nodeid').val());
	    fd.append('file', $('input[type=file]')[0].files[0]);
	    $.ajax({
	      url: "/ajax/apply-job",
	      type:'POST',
	      dataType: "json",
	      processData: false,
 		  contentType: false,
	      data: fd,
	      success: function(response) {
            if($(this).hasClass('fileUpload')) {
                $('#uploadresume').append('<div class="errorMsg">'+response.message+'</div>');
            } else {
                $('.btnsec').append('<div class="errorMsg">'+response.message+'</div>');
            }
	        
	      }
	    });
	});

	$('.postajob').click(function(event) {
	    event.preventDefault();
	    if(isIE()>=13){
		    var jobtitle = $('#jobtitle option:selected').val();
		    var jobfunction = $("#function option:selected").val();
		    var orgname = $('#organisationname option:selected').val();
		    var country = $('#countryId option:selected').attr('id');
		    var state = $('#stateId option:selected').val();
		    var city = $('#cityId option:selected').val();
		    var minexp = $('#minexp option:selected').val();
		    var maxexp = $('#maxexp option:selected').val();
		    var jobdesc = $('#jobdesc').val();
		    var uploadid = $('#uploadid').val();
		    var error = false;
		    $('.errorMsg').remove();
		    $.ajax({
		      url: "/ajax/post-a-job",
		      type:'POST',
		      dataType: "json",
		      data: {
		        'token':$('#csrftoken').val(),
		        'jobtitle':jobtitle,
		        'jobfunction':jobfunction,
		        'orgname':orgname,
		        'country':country,
		        'state':state,
		        'city':city,
		        'minexp':minexp,
		        'maxexp':maxexp,
		        'jobdesc':jobdesc,
		        'uploadid':uploadid
		      },
		      success: function(response) {
		        //$('#csrftoken').val(response.tok);
		        if(response.status == 'success') {
		            $('form#postajobForm').remove();
		            $('#block-postajob').hide();
		            $('.content').append('<div class="messages messages--status">'+ response.msg+'</div>');
		        } else {
		            for(var x in response.error) {
		            var temp    = response.error[x].split("-");
		            var errID   = temp[0];
		            var errMsg  = temp[1];
		            $('#'+errID).after('<span class="errorMsg">'+ errMsg +'</span>');
		          }
		        }
		      }
		    });
		} else {
			$('form#postajobForm').submit();
		}
	});

	/*document.getElementById('inputFile').onchange = function () {
	  	var currentfile = this.value;
	  	var filename = currentfile.split('\\').pop();
	  	$('.uploadedFileName').text(filename);
	};*/
	if(isIE()=="9" || isIE()=="10" || isIE()=="11" || isIE()=="12"){
		$('li.uploadli').hide();
		$('li.ie9upload').show();
	}

})(jQuery);

function showname () {
  var name = document.getElementById('inputFile');
  return name.files.item(0).name;
};

function isIE () {
  /*var myNav = navigator.userAgent.toLowerCase();
  return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;*/
  	var rv = -1; // Return value assumes failure.

    if (navigator.appName == 'Microsoft Internet Explorer'){

       var ua = navigator.userAgent,
           re  = new RegExp("MSIE ([0-9]{1,}[\\.0-9]{0,})");

       if (re.exec(ua) !== null){
         rv = parseFloat( RegExp.$1 );
       }
    }
    else if(navigator.appName == "Netscape"){                       
       /// in IE 11 the navigator.appVersion says 'trident'
       /// in Edge the navigator.appVersion does not say trident
       if(navigator.appVersion.indexOf('Trident') === -1) rv = 12;
       else rv = 11;
    }       

    return rv;
}


