(function ($) {
  
  $('input[type=tel]').keypress(function(e){ 
    if (this.value.length == 0 && e.which == 48 ){
      return false;
    }
  });
  
  if($('body').hasClass('section-my-account')) {
    
    $('.formFields li').each(function() {
      if($(this).hasClass('addrChkd')) {
        $(this).find('.addrChk').trigger('click');
        $('.usrAddrCntry').addClass('dnone');
        $('.usrAddrCty').addClass('dnone');
        $('.usrAddrState').addClass('dnone');
      }
    });
    var progress = $('.progress span').text();
    progress = parseInt(progress.replace('%', ''));
    if(progress > 100) {
      progress = 100 - 2;
    }
    $('.progress span').text(progress+'%');
    $('.progress').css('width', progress+'%');
    
    
  }
  
  $('.innerbanner').each(function() {
    if($(this).children().length == 0) {
      $(this).remove();
    }
  });
  
  var myClass = $('body').attr('class');
  if(myClass.indexOf('job-listing') !== -1) {
    //$('.container').eq(1).remove();
  }
  
  if($('body').hasClass('page-alumni-network-join-the-network-student-listing') || $('body').hasClass('page-alumni-network-join-the-network-alumni-listing') || $('body').hasClass('page-alumni-network-join-the-network-faculty-listing')) {
    $(document).on('change', '.userType', function() {
      if($(this).val() == 'alumni') {
        window.location = '/alumni-network/join-the-network/alumni-listing';
      }
      if($(this).val() == 'student') {
        window.location = '/alumni-network/join-the-network/student-listing';
      }
      if($(this).val() == 'faculty') {
        window.location = '/alumni-network/join-the-network/faculty-listing';
      }
    });
  }
  
  if($('body').hasClass('page-alumni-network-join-the-network-alumni-listing')) {
    var cities = [];
    
    
    $('.allCtsList').hide();
    $(document).on('keyup', '#allCities', function() {
      var strTxt = $(this).val();
      if(strTxt.length > 2) {
        $.ajax({
          url:  "/ajax/get-all-cities",
          type: "POST",
          data:  {'strTxt':strTxt},
          success: function(data){
            console.log(data);
            if(data != '') {
              $('.allCtsList').html('').show();
              $('.allCtsList').append(data);
            }
          },	        
        });
      }
    });
    
    $(document).on('click', '.allCtsList a', function() {
      $('#allCities').val('');
      $('#allCities').val($(this).text());
      $('.allCtsList').html('').hide();
    });
    
    $(document).keyup(function(e){
      if (e.keyCode == 13){
        $(".alumListSrchBtn").trigger('click');       
      }
    })
    
  }  
  
  if($('body').hasClass('page-front')) {
    if($('.eventList').hasClass('noEvents')) {
      $('.event').find('.button').remove();
    }
  }
  
  if($('body').hasClass('page-alumni-network-alumni-news-alumni-newsletter')) {
    $.ajax({
      url: "/ajax/newsletter",
      type:'POST',
      dataType: "json",
      data: {},
      success: function(response) {
        $('.subscribeNewsLttr #csrfToken').val(response.token);
        $('.newsletter_list select').append(response.options);
        $('.newsletter_list').find('.shortDropLink').text(response.selected);
      }
    });
    
    $(document).on('change', '.newsletter_list select', function() {
      var year = $(this).val();
      $.ajax({
        url: "/ajax/newsletter-filter",
        type:'POST',
        dataType: "json",
        data: {'yearNL': year},
        success: function(response) {
          $('#block-alumninewsletter').html('');
          $('#block-alumninewsletter').html(response.data);
        }
      });
    });
  }
  
  $('.breadcrumb ul li:not(:last-child)').css('color', '#b30608');
  $('.breadcrumb li:last span').remove();
  
  if($.trim($('.breadcrumb li').eq(1).text()) == 'Irma') {
    $('.breadcrumb li').eq(1).css('text-transform', 'uppercase');
  }
  
  if($('.detailContent').eq(0).hasClass('trigger') || $('.detailContent').eq(1).hasClass('trigger')) {
    $('.mainWrapper').find('.mainWrapper').remove();
    $('#content-area').find('p').remove();
    $('.Login').addClass('openLogin');
  }
  
  if($('body').hasClass('page-irma-get-involved-co-develop-a-case-study-apply') || $('body').hasClass('page-irma-get-involved-invite-faculty-to-conduct-workshop-apply') || $('body').hasClass('page-irma-get-involved-collaborate-on-a-project-apply') || $('body').hasClass('page-irma-get-involved')) {
    setTimeout(function() {
      $('.innerbanner').eq(1).remove();
      $('.left-col').eq(1).remove(); 
    },500);
    $('.projStart, .csStart, .workStart').datepicker({
      dateFormat: 'dd/mm/yy',
      changeMonth: true,
      changeYear: true
    });
    $('.projEnd, .csEnd, .workEnd').datepicker({
      dateFormat: 'dd/mm/yy',
      changeMonth: true,
      changeYear: true
    });    
  }
  
  if($('body').hasClass('section-alumni-network')) {
    $('.formFealds').eq(1).remove();
    
    $('.arrDate, .departDate').datepicker({
      dateFormat: 'dd/mm/yy',
      changeMonth: true,
      changeYear: true
    });
    
    $('input[name=pickUpReq]').click(function() {
      if($(this).val() == 'Yes') {
        $('.pkUpLoc').removeClass('dnone');
      } else {
        $('.pkUpLoc').addClass('dnone');
      }
    });
    $('input[name=dropReq]').click(function() {
      if($(this).val() == 'Yes') {
        $('.dropLoc').removeClass('dnone');
      } else {
        $('.dropLoc').addClass('dnone');
      }
    });
    $('input[name=stayPref]').click(function() {
      if($(this).val() == 'IRMA Hostel') {
        $('.HostPref').removeClass('dnone');
      } else {
        $('.HostPref').addClass('dnone');
      }
    });
    
    
  }
  
  if($('body').hasClass('page-alumni-network-give-to-iaa-apply')) {
    $('.formFealds').eq(1).remove();
    
    $(".pan").on('input', function(evt) {
      var input = $(this);
      var start = input[0].selectionStart;
      $(this).val(function (_, val) {
        return val.toUpperCase();
      });
    }); 
    
    $(document).on('change', '.giveTo', function() {
      if($(this).val() == 'Other') {
        $('.purpose').removeClass('dnone');
      } else {
        $('.purpose').addClass('dnone');
      }
    });
    
    $(document).on('click', '.addrChk', function() {
      if($(this).is(':checked')) {
        $('.addr1').val($('#addr1').val());
        $('.addr2').val($('#addr2').val());
        $('.addr3').val($('#addr3').val());
      } else {
        $('.addr1').val('');
        $('.addr2').val('');
        $('.addr3').val('');
      }
    });
  }
  
  if($('body').hasClass('page-irma-avail-campus-facilities-management-development-program-apply') || $('body').hasClass('page-irma-avail-campus-facilities-campus-infrastructure-etdc-apply') || $('body').hasClass('page-irma-avail-campus-facilities-campus-infrastructure-wifi-access-apply') || $('body').hasClass('page-irma-avail-campus-facilities-campus-infrastructure-sac-apply') || $('body').hasClass('page-irma-avail-campus-facilities-campus-infrastructure-students-mess-apply') || $('body').hasClass('page-irma-avail-campus-facilities-campus-infrastructure-library-apply')) {
    $('.innerbanner').eq(1).remove();
    $('.arrDate, .startDate').datepicker({
      dateFormat: 'dd/mm/yy',
      changeMonth: true,
      changeYear: true
    });
    $('.depDate, .enDate').datepicker({
      dateFormat: 'dd/mm/yy',
      changeMonth: true,
      changeYear: true
    });
  }

  if($('body').hasClass('role--administrator')) {
    $('#header').css('position', 'relative');
    $('.innerbanner').css('padding', '0');
    $('.banner-carousal').css('margin', '0');
  }
  
  if($('body').hasClass('section-my-account')) {    
    $('.empFrom, .empTo, .dob').datepicker({
      dateFormat: 'dd/mm/yy',
      changeMonth: true,
      changeYear: true,
      yearRange : '1979:',
      maxDate: '0'
    });
    setTimeout(function() {
      $('.innerbanner').eq(1).remove();
      $('.left-col').eq(1).remove();
      $('.right-col').eq(1).remove();
    },300);
    
    $(document).on('click', '.addrChk', function() {
      if($(this).is(':checked')) {
        $('.perAddr1').val($('.addr1').val());
        $('.perAddr2').val($('.addr2').val());
        $('.perAddr3').val($('.addr3').val());
        $('.usrAddrCntry').addClass('dnone');
        $('.usrAddrState').addClass('dnone');
        $('.usrAddrCty').addClass('dnone');
      } else {
        $('.perAddr1').val('');
        $('.perAddr2').val('');
        $('.perAddr3').val('');
        $('.usrAddrCntry').removeClass('dnone');
        $('.usrAddrState').removeClass('dnone');
        $('.usrAddrCty').removeClass('dnone');
      }
    });
    setTimeout(function() {
      var gender = $.trim($('.gendRad').attr('rel'));
      if(gender.toLowerCase() == 'male') {
        $('#box3').click();
      } else {
        $('#box4').click();
      }
    }, 800);
    
    $(document).on('focus', '#cityId', function() {
      if($('#stateId option:selected').text() == 'Select') {
        $('.ctList').after('<div class="errorMsg">Please select a State first</div>');
      } else {
        $('.regCity').find('.errorMsg').remove();
      }
    });
    
    $(document).on('change', '.courseType', function() {
      if($(this).val() == 'OTHER') {
        $('.crsName').removeClass('dnone');
      } else {
        $('.crsName').addClass('dnone');
      }
    });
    
  }
  
  $(document).on('click', '.eventRegnBtn', function() {
    $('.errorMsg').remove();
    $.ajax({
      url:  "/ajax/event-registration",
      dataType: "json",
      type: "POST",
      data:  {
        'userData'    : $('.formFealds').attr('id'),
        'evntName'    : $('.evntName').val(),
        'fName'       : $('.fName').val(),
        'lName'       : $('.lName').val(),
        'batch'       : $('.batch option:selected').text(),
        'organisation': $('.organisation option:selected').text(),
        'designation' : $('.designation').val(),
        'mail'        : $('.mail').val(),
        'country'     : $('.countries option:selected').text(),
        'state'       : $('.states option:selected').text(),
        'cities'      : $('.cities option:selected').text(),
        'telCode'     : $('.telCode').val(),
        'mobileNo'    : $('.mobileNo').val(),
        'arrDate'     : $('.arrDate').val(),
        'pickUpReq'   : $('input[name=pickUpReq]:checked').val(),
        'pickUpLox'   : $('.pickUpLox').val(),
        'departDate'  : $('.departDate').val(),
        'dropReq'     : $('input[name=dropReq]:checked').val(),
        'dropLox'     : $('.dropLox').val(),
        'noFGuests'   : $('.noFGuests option:selected').text(),
        'foodPref'    : $('input[name=foodPref]:checked').val(),
        'stayPref'    : $('input[name=stayPref]:checked').val(),
        'prefHostel'  : $('.prefHostel').val(),
        'token'       : $('#csrftoken').val()
      },
      success: function(data){
        $('#csrftoken').val(data.tok);
        if(data.status == 'success') {
          $('.formFealds').html('');
          $('.formFealds').append('<div class="messages messages-status">'+ data.mesg +'</div>');
        } else {
          for(var x in data.mesg) {
            var temp    = data.mesg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
        console.log(data);
      },	        
    });
  });
  
  $(document).on('click', '.giveToBtn', function() {
    $('.errorMsg').remove();
    $.ajax({
      url:  "/ajax/give-to-iaa-apply",
      dataType: "json",
      type: "POST",
      data:  {
        'userData'    : $('.formFealds').attr('id'),
        'fName'       : $('.fName').val(),
        'lName'       : $('.lName').val(),
        'batch'       : $('.batch option:selected').text(),
        'organisation': $('.organisation option:selected').text(),
        'designation' : $('.designation').val(),
        'mail'        : $('.mail').val(),
        'country'     : $('.countries option:selected').text(),
        'state'       : $('.states option:selected').text(),
        'citie'      : $('.cities option:selected').text(),
        'telCode'     : $('.telCode').val(),
        'mobileNo'    : $('.mobileNo').val(),
        'addr1'       : $('.addr1').val(),
        'addr2'       : $('.addr2').val(),
        'addr3'       : $('.addr3').val(),
        'ccity'       : $('.ccity').val(),
        'province'    : $('.province').val(),
        'pCode'       : $('.pCode').val(),
        'cCntry'      : $('.cCntry').val(),
        'giveTo'      : $('.giveTo option:selected').text(),
        'payPurpose'  : $('.payPurpose').val(),
        'pan'         : $('.pan').val(),
        'donAmt'      : $('.donAmt').val(),
        'anonDonate'  : $('.anonDonate').is(':checked'),
        'token'       : $('#csrftoken').val()
      },
      success: function(data){
        $('#csrftoken').val(data.tok);
        if(data.status == 'success') {
          $('.formFealds').html('');
          $('.formFealds').html('<div class="messages messages-status">'+ data.mesg +'</div>');
        } else {
          for(var x in data.mesg) {
            var temp    = data.mesg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
        console.log(data);
      },	        
    });
  });
  $(document).on('click', '.librar', function() {
    console.log('in here');
    $.ajax({
      url:  drupalSettings.path.baseUrl + "ajax/campus-infra-lib",
      dataType: "json",
      type: "POST",
      data:  {
        'userData'    : $('.formFealds').attr('rel'),
        'userData'    : $('.formFealds').attr('rel'),
        'fname'       : $('.fName').val(),
        'lname'       : $('.lName').val(),
        'batch'       : $('.batchNo option:selected').text(),
        'org'         : $('.organisation option:selected').text(),
        'design'      : $('.design').val(),
        'email'       : $('.email').val(),
        'cntry'       : $('.country option:selected').text(),
        'state'       : $('.states option:selected').text(),
        'city'        : $('.cityList option:selected').text(),
        'code'        : $('.telCode').val(),  
        'mobile'      : $('.mobileNo').val(),
        'arrDate'     : $('.arrDate').val(),
        'arrHr'       : $('.arrHr').val(),
        'arrHr'       : $('.arrMin').val(),
        'depDate'     : $('.depDate').val(),
        'depHr'       : $('.depHr').val(),
        'depMin'      : $('.depMin').val(),
        'purpVisit'   : $('input[name=purpVisit]').val(),
        'purpose'     : $('.purpose').val(),
        'accLib'      : $('input[name=accLib]').val(),
        'token'       : $('#csrftoken').val()
      },
      success: function(data){
        console.log(data);
      },	        
    });
  });
  $(document).on('click', '.arrFood', function() {
    $.ajax({
      url:  drupalSettings.path.baseUrl + "ajax/campus-infra-food",
      dataType: "json",
      type: "POST",
      data:  {
        'userData'    : $('.formFealds').attr('rel'),
        'fname'       : $('.fName').val(),
        'lname'       : $('.lName').val(),
        'batch'       : $('.batchNo option:selected').text(),
        'org'         : $('.organisation option:selected').text(),
        'design'      : $('.design').val(),
        'email'       : $('.email').val(),
        'cntry'       : $('.country option:selected').text(),
        'state'       : $('.states option:selected').text(),
        'city'        : $('.cityList option:selected').text(),
        'code'        : $('.telCode').val(),  
        'mobile'      : $('.mobileNo').val(),
        'arrDate'     : $('.arrDate').val(),
        'arrHr'       : $('.arrHr').val(),
        'arrHr'       : $('.arrMin').val(),
        'depDate'     : $('.depDate').val(),
        'depHr'       : $('.depHr').val(),
        'depMin'      : $('.depMin').val(),
        'purpVisit'   : $('input[name=purpVisit]:checked').val(),
        'purpose'     : $('.purpose').val(),
        'arrFood'     : $('input[name=arrFood]:checked').val(),
        'noPers'      : $('.noPers').val(),
        'token'       : $('#csrftoken').val()
      },
      success: function(data){
        console.log(data);
      },	        
    });
  });
  $(document).on('click', '.btnEtdc', function() {
    $('.messages').remove();
    $('.errorMsg').remove();
    $.ajax({
      url: "/ajax/campus-infra-etdc",
      dataType: "json",
      type: "POST",
      data:  {
        'userData'    : $('.formFealds').attr('rel'),
        'fname'       : $('.fName').val(),
        'lname'       : $('.lName').val(),
        'batch'       : $('.batchNo option:selected').text(),
        'org'         : $('.organisation option:selected').text(),
        'design'      : $('.design').val(),
        'email'       : $('.email').val(),
        'cntry'       : $('.country option:selected').text(),
        'state'       : $('.states option:selected').text(),
        'city'        : $('.cityList option:selected').text(),
        'code'        : $('.telCode').val(),  
        'mobile'      : $('.mobileNo').val(),
        'arrDate'     : $('.arrDate').val(),
        'arrHr'       : $('.arrHr').val(),
        'arrMin'      : $('.arrMin').val(),
        'depDate'     : $('.depDate').val(),
        'depHr'       : $('.depHr').val(),
        'depMin'      : $('.depMin').val(),
        'purpVisit'   : $('input[name=purpVisit]:checked').val(),
        'purpose'     : $('.purpose').val(),
        'numRooms'    : $('.numRooms').val(),
        'noPers'      : $('.numPers option:selected').val(),
        'incFood'     : $('input[name=incFood]:checked').val(),
        'prefFood'    : $('input[name=prefFood]:checked').val(),
        'token'       : $('#csrftoken').val()
      },
      success: function(data){
        $('#csrftoken').val(data.tok);
        if(data.status == 'success') {
          $('.formFealds').remove();
          $('.accountInfoSec').eq(0).after('<div class="messages messages--status">'+ data.msg +'</div>');
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        } else {
          for(var x in data.msg) {
            var temp    = data.msg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
      },	        
    });
  });
  $(document).on('click', '.btnWifi', function() {
    $('.messages').remove();
    $('.errorMsg').remove();
    $.ajax({
      url: "/ajax/campus-infra-wifi",
      dataType: "json",
      type: "POST",
      data:  {
        'userData'    : $('.formFealds').attr('rel'),
        'fname'       : $('.fName').val(),
        'lname'       : $('.lName').val(),
        'batch'       : $('.batchNo option:selected').text(),
        'org'         : $('.organisation option:selected').text(),
        'design'      : $('.design').val(),
        'email'       : $('.email').val(),
        'cntry'       : $('.country option:selected').text(),
        'state'       : $('.states option:selected').text(),
        'city'        : $('.cityList option:selected').text(),
        'code'        : $('.telCode').val(),  
        'mobile'      : $('.mobileNo').val(),
        'arrDate'     : $('.arrDate').val(),
        'arrHr'       : $('.arrHr').val(),
        'arrMin'      : $('.arrMin').val(),
        'depDate'     : $('.depDate').val(),
        'depHr'       : $('.depHr').val(),
        'depMin'      : $('.depMin').val(),
        'purpVisit'   : $('input[name=purpVisit]:checked').val(),
        'purpose'     : $('.purpose').val(),
        'accessWifi'  : $('input[name=accWifi]:checked').val(),
        'token'       : $('#csrftoken').val()
      },
      success: function(data){
        $('#csrftoken').val(data.tok);
        if(data.status == 'success') {
          $('.formFealds').remove();
          $('.accountInfoSec').eq(0).after('<div class="messages messages--status">'+ data.msg +'</div>');
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        } else {
          for(var x in data.msg) {
            var temp    = data.msg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
      },	        
    });
  });
  $(document).on('click', '.btnSac', function() {
    $('.messages').remove();
    $('.errorMsg').remove();
    $.ajax({
      url: "/ajax/campus-infra-sac",
      dataType: "json",
      type: "POST",
      data:  {
        'userData'    : $('.formFealds').attr('rel'),
        'fname'       : $('.fName').val(),
        'lname'       : $('.lName').val(),
        'batch'       : $('.batchNo option:selected').text(),
        'org'         : $('.organisation option:selected').text(),
        'design'      : $('.design').val(),
        'email'       : $('.email').val(),
        'cntry'       : $('.country option:selected').text(),
        'state'       : $('.states option:selected').text(),
        'city'        : $('.cityList option:selected').text(),
        'code'        : $('.telCode').val(),  
        'mobile'      : $('.mobileNo').val(),
        'arrDate'     : $('.arrDate').val(),
        'arrHr'       : $('.arrHr').val(),
        'arrMin'      : $('.arrMin').val(),
        'depDate'     : $('.depDate').val(),
        'depHr'       : $('.depHr').val(),
        'depMin'      : $('.depMin').val(),
        'purpVisit'   : $('input[name=purpVisit]:checked').val(),
        'purpose'     : $('.purpose').val(),
        'accessSac'   : $('input[name=accSac]:checked').val(),
        'token'       : $('#csrftoken').val()
      },
      success: function(data){
        $('#csrftoken').val(data.tok);
        if(data.status == 'success') {
          $('.formFealds').remove();
          $('.accountInfoSec').eq(0).after('<div class="messages messages--status">'+ data.msg +'</div>');
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        } else {
          for(var x in data.msg) {
            var temp    = data.msg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
      },	        
    });
  });
  $(document).on('click', '.btnMess', function() {
    $('.messages').remove();
    $('.errorMsg').remove();
    $.ajax({
      url: "/ajax/campus-infra-stdtmess",
      dataType: "json",
      type: "POST",
      data:  {
        'userData'    : $('.formFealds').attr('rel'),
        'fname'       : $('.fName').val(),
        'lname'       : $('.lName').val(),
        'batch'       : $('.batchNo option:selected').text(),
        'org'         : $('.organisation option:selected').text(),
        'design'      : $('.design').val(),
        'email'       : $('.email').val(),
        'cntry'       : $('.country option:selected').text(),
        'state'       : $('.states option:selected').text(),
        'city'        : $('.cityList option:selected').text(),
        'code'        : $('.telCode').val(),  
        'mobile'      : $('.mobileNo').val(),
        'arrDate'     : $('.arrDate').val(),
        'arrHr'       : $('.arrHr').val(),
        'arrMin'      : $('.arrMin').val(),
        'depDate'     : $('.depDate').val(),
        'depHr'       : $('.depHr').val(),
        'depMin'      : $('.depMin').val(),
        'purpVisit'   : $('input[name=purpVisit]:checked').val(),
        'purpose'     : $('.purpose').val(),
        'arrFood'     : $('input[name=arrFood]:checked').val(),
        'numPerson'   : $('.noPers option:selected').text(),
        'token'       : $('#csrftoken').val()
      },
      success: function(data){
        $('#csrftoken').val(data.tok);
        if(data.status == 'success') {
          $('.formFealds').remove();
          $('.accountInfoSec').eq(0).after('<div class="messages messages--status">'+ data.msg +'</div>');
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        } else {
          for(var x in data.msg) {
            var temp    = data.msg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
      },	        
    });
  });
  $(document).on('click', '.btnLibr', function() {
    $('.messages').remove();
    $('.errorMsg').remove();
    $.ajax({
      url: "/ajax/campus-infra-library",
      dataType: "json",
      type: "POST",
      data:  {
        'userData'    : $('.formFealds').attr('rel'),
        'userData'    : $('.formFealds').attr('rel'),
        'fname'       : $('.fName').val(),
        'lname'       : $('.lName').val(),
        'batch'       : $('.batchNo option:selected').text(),
        'org'         : $('.organisation option:selected').text(),
        'design'      : $('.design').val(),
        'email'       : $('.email').val(),
        'cntry'       : $('.country option:selected').text(),
        'state'       : $('.states option:selected').text(),
        'city'        : $('.cityList option:selected').text(),
        'code'        : $('.telCode').val(),  
        'mobile'      : $('.mobileNo').val(),
        'arrDate'     : $('.arrDate').val(),
        'arrHr'       : $('.arrHr').val(),
        'arrMin'      : $('.arrMin').val(),
        'depDate'     : $('.depDate').val(),
        'depHr'       : $('.depHr').val(),
        'depMin'      : $('.depMin').val(),
        'purpVisit'   : $('input[name=purpVisit]:checked').val(),
        'purpose'     : $('.purpose').val(),
        'accLibr'     : $('input[name=accLib]:checked').val(),
        'token'       : $('#csrftoken').val()
      },
      success: function(data){
        $('#csrftoken').val(data.tok);
        if(data.status == 'success') {
          $('.formFealds').remove();
          $('.accountInfoSec').eq(0).after('<div class="messages messages--status">'+ data.msg +'</div>');
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        } else {
          for(var x in data.msg) {
            var temp    = data.msg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
      },	        
    });
  });
  $(document).on('click', '.btnMdp', function() {
    $('.messages').remove();
    $('.errorMsg').remove();
    $.ajax({
      url: drupalSettings.path.baseUrl + "ajax/mdp-apply",
      dataType: "json",
      type: "POST",
      data:  {
        'userData'    : $('.formFealds').attr('rel'),
        'fname'       : $('.fName').val(),
        'lname'       : $('.lName').val(),
        'batch'       : $('.batchNo option:selected').text(),
        'org'         : $('.organisation option:selected').text(),
        'design'      : $('.design').val(),
        'email'       : $('.email').val(),
        'cntry'       : $('.country option:selected').text(),
        'state'       : $('.states option:selected').text(),
        'city'        : $('.cityList option:selected').text(),
        'code'        : $('.telCode').val(),  
        'mobile'      : $('.mobileNo').val(),
        'mdpName'     : $('.mdpName').val(),
        'startDate'   : $('.startDate').val(),
        'enDate'      : $('.enDate').val(),
        'mdpQry'      : $('.mdpQry').val(),
        'token'       : $('#csrftoken').val()
      },
      success: function(data){
        $('#csrftoken').val(data.tok);
        if(data.status == 'success') {
          $('.formFealds').remove();
          $('.accountInfoSec').eq(0).after('<div class="messages messages--status">'+ data.msg +'</div>');
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        } else {
          for(var x in data.msg) {
            var temp    = data.msg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
      },	        
    });
  });
  if($('body').hasClass('page-register')) {
    $.ajax({
      url:  "/ajax/dropdown-options",
      type: "POST",
      data:  {'page': 'register'},
      success: function(data){
        $('.batch').append(data.batchNo);
        $('.areaGrp').append(data.areaGrp);
        $('.subGrp').append(data.subjGrp);
        $('.courseType').append(data.courseType);
      },	        
    });
    
    $(".dobReg").datepicker({
      dateFormat: 'dd/mm/yy',
      changeMonth: true,
      changeYear: true,
      yearRange: '1979:',
      maxDate: '0'
    });

    $(document).on("change", ".courseType", function() {
      var course = $(this).val();
      if($(this).val() == 'OTHER') {
        $(this).closest('.regFrm').find('.course').parent('li').removeClass('dnone');    
      } else {
        $(this).closest('.regFrm').find('.course').parent('li').addClass('dnone'); 
      }
      
      var joinYr = $(this).closest('.regFrm').find('#joinYear').val();
      if(joinYr == '2017' && course == 'PRM') {
        $(this).closest('.regFrm').find('.batch').val('PRM-37').attr('readonly', 'readonly');
      } else {
        $(this).closest('.regFrm').find('.batch').val('Batch Number').removeAttr('readonly');
      }
    });
  }
  
  $(document).on('change', '#courseType', function() {
    var course = $(this).val();
  });

  
  
  
  $(document).on('click', '.goBtn', function() {
    var srchVal = $('#srchFrm input').val();
    if(srchVal == '') {
      alert('Search Text Missing');
    } else {
      $('#srchFrm').submit();
    }
      
  });
  
  $('.funImg').click(function() {
    $('.FileFun').trigger('click');
    return false;
  });
  
  $("#funImgFrm").on('submit',(function(e){
    $('.errorMsg').remove();
    var _this = $(this);
    $('.errorMsg').remove();
    e.preventDefault();
    $.ajax({
      url:  "/ajax/image-upload",
      type: "POST",
      data:  new FormData(this),
      contentType: false,
      cache: false,
      processData:false,
      success: function(data){
        console.log(data);
        if(data.error == 1) {
          if($(this).hasClass('profImg')) {
            _this.find('.buttonSec').after('<span class="errorMsg">'+ data.message +'</span>');
          } else {
            $('#funImgFrm').append('<span class="errorMsg">'+ data.message +'</span>');
          }
        } else {
          _this.find('img').attr('src', data.file);
          $('#fntoken').val(data.fid);
        }
      },
      error: function(){
      } 	        
    });
  }));
    
  $(".fileUpload").click(function() {
    if($('body').hasClass('page-alumni-network-careers-post-a-job')) {
      $('.uploadBtn .errorMsg, .uplFile').remove();
      var _this = $(this);
      var options = {
        beforeSend: function() {},
        success: function(response) {
          if($('body').hasClass('section-my-account')) {
            $('#cvtoken').val(response.fid);
            $('.fileUpload').after('<div class="uplFile">'+ response.filename +'</div>');
          } else {
              $('#postajobForm').find('#uploadid').val(response.fid);
            if(response.error==1){
              $('.messages--status').css({"background-color":"#fee3dd","display":"block","color":"#b22000"});
              $('.messages--status').text(response.message);
            } else {
               $('.messages--status').css({"background-color":"#f3faef","display":"block","color":"#009310"});
               $('.messages--status').text('File attached successfully');
            }
          }
        },
        complete: function(response) { },
        error: function() {
          if($('body').hasClass('section-my-account')) {
            $('#fileForm').after('<span class="errorMsg">'+ response.message +'</span>');
          } else {
  
          }
        }
      };
      _this.parents("#fileForm").ajaxForm(options);
    }
  });
  
  $('.uploadBtn').click(function() {
    $(this).closest('form').find('input[name=file]').click();
  });
  
  var temp = '';
  
  $('input[type=file]').change(function() {
    if($(this).hasClass('FileFun')) {
      $('#funImgFrm').submit();
      return false;
    }
    if($(this).hasClass('cvFile')) {
      $('.fileUpload').trigger('click');
    }
    if($('body').hasClass('section-my-account') && $(this).hasClass('profileImage')) {
      temp = $('.profImf').val();
      $('.profImf').val('Upload profile image');
      setTimeout(function() {
        $('.profImf').trigger('click');
      },200);
    }
    if($('body').hasClass('page-register')) {
      $('.profImf').val('Upload profile image');
      setTimeout(function() {
        $('.profImf').trigger('click');
      },200);
    }
  });


  $("#uploadFrm").on('submit',(function(e){
    e.preventDefault();
    if($('body').hasClass('page-register')) {
      if($('.profImf').val() == 'Add a Profile Image' || $('.profImf').val() == 'Change Profile Image') {
        $('input[type=file]').eq(0).trigger('click');
        return false;
      }
    }
    if($('body').hasClass('section-my-account')) {
      if($('.profImf').val() == 'Change Profile Image' || $('.profImf').val() == 'Add a Profile Image') {
        $('input[type=file]').eq(0).trigger('click');
        return false;
      }
    }
    var _this = $(this);
    var formData = new FormData(this);
    
    $('.errorMsg').remove();
    $.ajax({
      url         : "/ajax/image-upload",
      type        : "POST",
      dataType    : "json",
      data        : formData,
      contentType : false,
      cache       : false,
      processData : false,
      success     : function(data){
        if($('body').hasClass('page-register')) {
          if(data.message == 'success') {
            $('#tokenfid').val(data.fid);
            $('#tokennid').val(data.nnid);
            $('.imgProfile').find('img').remove();
            $('.imgProfile').prepend('<img src="' + data.file + '">');
            $('.profImf').val('Change Profile Image');
            //return false;
          } else {
            $('#uploadFrm').after('<div class="errorMsg">'+ data.message +'</div>')
          }
        } else {
          if(data.error == 1) {
            $('#uploadFrm').after('<span class="errorMsg">'+ data.message +'</span>');
            $('.profImf').val(temp);
            if($(this).hasClass('funImg')) {
              $('.uploadBtn').after('<span class="errorMsg">'+ data.message +'</span>');
            }
          } else {
            _this.find('img').attr('src', data.file);
            _this.find('.profImf').val('Change Profile Image');
            $('#fidtoken').val(data.fid);

            if($(this).hasClass('profImg')) {
              $('.imgProfile').eq(0).find('img').attr('src', data.file);
              $('#fntoken').val(data.fid);
            }
          }
        }
      },
      error: function(){ } 	        
    });
  }));
   
  $(document).on('click', '.searchBtn', function() {
    var srchVal = $('#srchSecFrm input:first').val();
    if(srchVal == '') {
      alert('Search Text Missing');
    } else {
      $('#srchSecFrm').submit();
    }
  });
  
  if($('body').hasClass('page-search')) {
    /*var cnt = $('.srchHidden').attr('rel');
    if(cnt <= 10) {
      var myTxt = 'Showing '+ cnt +' of '+ cnt;
    } else {
      var myTxt = 'Showing 10 of ' + cnt;
    }
    $('.tx_search').text(myTxt);*/
    $('#srchStr').val($('.srchHidden').text());
    $('.srchLoadMore').attr('href', 'javascript:;');
  }
   
  $(document).on('click', '.srchLoadMore', function() {
    var nid = $('.alSrchList li').last().attr('rel');
    var noTxt = $('.tx_search').text();
    var srchTxt = $("#srchStr").val();
    $.ajax({
      type:'POST',
      url: drupalSettings.path.baseUrl + "ajax/search",
      dataType: "json",
      data: {'nid':nid, 'srchStr': srchTxt, 'noTxt':noTxt},
      success: function(response) {
        if(response.data != '') {
          $('.alSrchList').append(response.data);
          $('.tx_search').text(response.pager_txt);
        }
        if(response.loadMore == 0) {
          $('.srchLoadMore').remove();
        }
      }
    });
  });
  $(document).on('click', '.loadMoreBtn', function() {
    var nid = $(this).attr('rel');
    var cat = $(this).attr('id');
    $.ajax({
      type:'POST',
      url: drupalSettings.path.baseUrl + "ajax/featured-alumni",
      dataType: "json",
      data: {'nid':nid, 'cat':cat},
      success: function(response) {
        if(response.data != '') {
          if(cat == 'exeCom') {
            $('#block-executivecommittee').append(response.data);
          } else {
            $('.alList').append(response.data);
          }
          
        }
        if(response.loadMore == 0) {
          var lm = parseInt(nid) + parseInt(response.lim);
          $(this).attr('rel', lm);
          $('.loadMoreBtn').remove();
        }
      }
    });
  });
  
  $(document).on('click', '.clickLightbox', function() {
    $('').not('a:first').remove();
    var nid = $(this).closest('li').attr('rel');
    $.ajax({
      type:'POST',
      url: drupalSettings.path.baseUrl + "ajax/featured-alumnus",
      dataType: "json",
      data: {'nid':nid},
      success: function(response) {
        $('#lightBox').append(response.data);
        //lightboxPopup();
      }
    });
  });
  
  $(document).on('click', '.loadMoreBr', function() {
    var nid = $(this).attr('rel');
    $.ajax({
      type:'POST',
      url: "/ajax/batchrepresentative",
      dataType: "json",
      data: {'nid':nid},
      success: function(response) {
        if(response.data != '') {
          $('.batchLoadMoreUL').append(response.data);
          $('.loadMoreBr').attr('rel', response.lim);
        }
        if(response.loadMore == '0') {
          $('.loadMoreBr').remove();
        }
      }
    });
  });
  
  $(document).on('click', '.loadMoreNews', function() {
    var nid = $(this).attr('rel');
    var sortVal = $('#newSort').val();
    $.ajax({
      type:'POST',
      url: drupalSettings.path.baseUrl + "ajax/alumni-news",
      dataType: "json",
      data: {'nid':nid, 'sortVal':sortVal},
      success: function(response) {
        if(response.data != '') {
          $('#block-alumninewslist').append(response.data);
        }
        if(response.loadMore == 0) {
          $('.loadMoreNews').remove();
        }
      }
    });
  });
  
  $(document).on('click', '.eventsLoadMore', function() {
    var nid = $('.alList li').last().attr('rel');
    if($('body').hasClass('page-alumni-network-events-archived-events')) {
      var page = 'archived'; 
    } else {
      var page = 'upcoming';
    }
    //var sortVal = $('#newSort').val();
    $.ajax({
      type:'POST',
      url: drupalSettings.path.baseUrl + "ajax/events",
      dataType: "json",
      data: {'nid':nid, 'page':page},
      success: function(response) {
        if(response.data != '') {
          $('.alList').append(response.data);
        }
        if(response.loadMore == 0) {
          $('.eventsLoadMore').remove();
        }
      }
    });
  });
  
  $(document).on('change', '#newSort', function() {
    var sortVal = $(this).val();
    $(".alList li").remove();
    $.ajax({
      type:'POST',
      url: drupalSettings.path.baseUrl + "ajax/alumni-news-sort",
      dataType: "json",
      data: {'sortVal':sortVal},
      success: function(response) {
        if(response.data != '') {
          $('#block-alumninewslist').append(response.data);
        }
        if(response.loadMore == 0) {
          $('.loadMoreNews').remove();
        } else {
          if(!$('.loadMoreNews').is(':visible')) {
            $('.alList').append('<a class="button loadMoreNews" href="javascript:;">Load more</a>');
          }  
        }
      }
    });
  });
  
  $(document).on('click', '.alumniNewSrch', function() {
    $('.messages').remove();
    //$('#newSrch').val('Search within news');
    var srchTxt = $('#newSrch').val();
    if(srchTxt == '' || srchTxt == 'Search within news') {
      $('#newSrch').val('Please enter Search Text');
      return false;
    }
    $.ajax({
      type:'POST',
      url: drupalSettings.path.baseUrl + "ajax/alumni-news-search",
      dataType: "json",
      data: {'srchTxt':srchTxt},
      success: function(response) {
        if(response.loadmore == '0') {
          $('.loadMoreNews').remove();
        } 
        if(response.data == '') {
          $(".alList li").remove();
          $('.alList').after('<span class="messages messages-status">No result found</span>');
        } else {
          $(".alList li").remove();
          $('#block-alumninewslist').append(response.data);
        }
      }
    });
  });
  
  $(document).ready(function() {
    var contactForm = $("#contactForm");
    //We set our own custom submit function
    contactForm.on("submit", function(e) {
      //Prevent the default behavior of a form
      e.preventDefault();
      //Get the values from the form
      var firstname = $("#firstname").val();
      var lastname = $("#lastname").val();
      var emailid = $("#emailid").val();
      var mobileno = $("#mobileno").val();
      var feedbackmsg = $("select#feedbackmsg option:selected").val();
      var message = $("#message").val();
      var error = false;
      var name_regex = /^[a-zA-Z]+$/;
      var email_regex = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
      var add_regex = /^[0-9a-zA-Z ]+$/;
      var mob_regex = /^[789][0-9]{9}$/;

      $('span.errorMessage').css('display','none');
      $('span.errorMessage').html('');

      if (firstname.length == 0) {
        $("span#firstnameErr").css("display","block");
        $("span#firstnameErr").html('First name is required');
        error = true;
      } else if (!firstname.match(name_regex) || firstname.length == 0) {
        $("span#firstnameErr").css("display","block");
        $("span#firstnameErr").html('Please enter valid First name');
        error = true;
      }

      if (lastname.length == 0) {
        $("span#lastnameErr").css("display","block");
        $("span#lastnameErr").html('Last name is required');
        error = true;
      } else if (!lastname.match(name_regex) || lastname.length == 0) {
        $("span#lastnameErr").css("display","block");
        $("span#lastnameErr").html('Please enter valid Last name');
        error = true;
      }

      if (!emailid.match(email_regex) || emailid.length == 0) {
        $("span#emailidErr").css("display","block");
        $("span#emailidErr").html('Please enter valid email id');
        error = true;
      }

      if (mobileno.length == 0) {
        $("span#mobilenoErr").css("display","block");
        $("span#mobilenoErr").html('Please enter valid mobile number');
        error = true;
      } else if (!mobileno.match(mob_regex) || mobileno.length == 0) {
        $("span#mobilenoErr").css("display","block");
        $("span#mobilenoErr").html('Please enter valid mobile number');
        error = true;
      }

      if(feedbackmsg=="feedback" || feedbackmsg==""){
        $("span#feedbackmsgErr").css("display","block");
        $("span#feedbackmsgErr").html('Please select feedback');
        error = true;
      }

      if (!message.match(add_regex) || message.length == 0) {
        $("span#messageErr").css("display","block");
        $("span#messageErr").html('Please enter valid message details');
        error = true;
      }

      //Our AJAX POST
      if(error==false){        
        $.ajax({
          type: "POST",
          url: "/ajax/check-contact-captcha",
          data: {
            //THIS WILL TELL THE FORM IF THE USER IS CAPTCHA VERIFIED.
            captcha: grecaptcha.getResponse()
          },
          success: function(resp) {
            //var obj = JSON.parse(resp);
            if(resp.response==false){
              grecaptcha.reset();
            } else {
              $.ajax({
                type: "POST",
                url: "/ajax/contact-form",
                data: {
                  firstname: firstname,
                  lastname: lastname,
                  emailid: emailid,
                  mobileno: mobileno,
                  feedbackmsg: feedbackmsg,
                  message: message
                },
                success: function(resp) {
                  //var obj = JSON.parse(resp);
                  if(resp.message=="Failure"){
                    grecaptcha.reset();
                    for(var x in resp.response) {
                      var temp = resp.response[x].split("-");
                      var errID = temp[0];
                      var errMsg = temp[1];
                      //console.log(errID);
                      $("span#"+errID).css("display","block");
                      $("span#"+errID).html(errMsg);
                      //$('#loaderdiv').hide();
                      //$('#loaderdiv1').hide();
                    }
                  }
                  else
                  {
                    $('form#contactForm').hide();
                    $('.hideline').hide();
                    $('div.formSec').html('<span class="successMessage">Thank you for contacting us. We will get back to you shortly.</span><i class="clrBoth"></i>');
                  }
                }
              });
            }
            console.log("OUR FORM SUBMITTED CORRECTLY");
          }
        });
      }
    });
  });
  
  $(document).on('change', '.selType', function() {
    $('.errorMsg').remove();
    if($(this).val() != 0) {
      $('.usrProfImg').removeClass('dnone');
      if($(this).val() == 1) {
        $('#alumniFrm').removeClass('dnone');
        $('#facultyFrm').addClass('dnone');
        $('#studentFrm').addClass('dnone');
      }
      if($(this).val() == 2) {
        $('#alumniFrm').addClass('dnone');
        $('#facultyFrm').removeClass('dnone');
        $('#studentFrm').addClass('dnone');
      }
      if($(this).val() == 3) {
        $('#alumniFrm').addClass('dnone');
        $('#facultyFrm').addClass('dnone');
        $('#studentFrm').removeClass('dnone');
      }
    } else {
      $('.usrProfImg').addClass('dnone');
      $('#alumniFrm').addClass('dnone');
      $('#facultyFrm').addClass('dnone');
      $('#studentFrm').addClass('dnone');
    }
  });

  $(document).on('click', '.chapterLoadMore', function() {
    var nid = $(this).attr('rel');
    $.ajax({
      type:'POST',
      url: drupalSettings.path.baseUrl + "ajax/chapters",
      dataType: "json",
      data: {'nid':nid},
      success: function(response) {
        if(response.data != '') {
          $('.chaptersLoadMoreUL').append(response.data);
          $('.chapterLoadMore').attr('rel', response.lim);
        }
        if(response.loadMore == 0) {
         // $('.chaptersLoadMoreUL').append("<p class='nomoredata'>No More Data</p>");
          $('.chapterLoadMore').remove();
        }
      }
    });
  });
  
  $(document).on('click', '.alumniReg', function() {
    $('.errorMsg, .messages').remove();
    var courseName = '';
    if($('#alumniFrm #courseType').val() == 'OTHER') {
      courseName = $('#alumniFrm #course').val(); 
    } 
    
    $.ajax({
      url: drupalSettings.path.baseUrl + "ajax/user-register",
      type:'POST',
      dataType: "json",
      data: {
        token       : $('#csrftoken').val(),
        eMail       : $('#alumniFrm #emailId').val(),
        fName       : $('#alumniFrm #firstName').val(),
        lName       : $('#lastName').val(),
        gender      : $('#alumniFrm input[name=gender]:checked').val(),
        dob         : $('#alumniFrm #dobReg').val(),
        addr1       : $('#alumniFrm #addr1').val(),
        addr2       : $('#alumniFrm #addr2').val(),
        addr3       : $('#alumniFrm #addr3').val(),
        country     : $("#alumniFrm #countryCode option:selected").text(),
        state       : $("#alumniFrm .states option:selected").text(),
        city        : $("#alumniFrm .cities option:selected").text(),
        mobile      : $('#alumniFrm #cCode').val() +'-'+$('#alumniFrm #mobNo').val(),
        courseType  : $('#alumniFrm #courseType :selected').text(),
        courseName  : courseName,
        password    : $.trim($('.ppassword').val()),
        c_password  : $.trim($('.cPassword').val()),
        joinYr      : $.trim($('#alumniFrm #joinYear :selected').text()),
        gradYr      : $('#alumniFrm #gradYear').val(),
        batchNo     : $('#alumniFrm .batch').val(),
        rollNo      : $('#alumniFrm #rollNo').val(),
        fid         : $('#tokenfid').val(),
        userType    : 'alumni',
      },
      success: function(response) {
        $('#csrftoken').val(response.tok);
        if(response.data == 'success') {
          $('.innerContainer').html('');
          $('.innerContainer').html('<div class=messages messages-status">'+response.msg +'</div>');
          $('.formPage').find('h3').text('Account Created Successfully');
          $(window).scrollTop($(".errorMsg:visible").offset().top-200);
        } else {
          for(var x in response.msg) {
            var temp    = response.msg[x].split('-');
            var fieldId = temp[0];
            var mesg    = temp[1];
            if(fieldId == 'formFealds') {
              $('.formFealds').prepend('<div class="messages messages--error">'+ mesg +'</div>');
              $(window).scrollTop($(".messages:visible").offset().top-125);
            } else {
              $('.'+ fieldId).after('<span class="errorMsg">'+ mesg +'</span>');
            }
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-200);
        }
      }
    }); 
  });
  
  
  $(document).on('click', '.stdtReg', function() {
    $('.errorMsg').remove();
    var error = false;
    var courseName = '';
    
    if($('#s_courseType').val() == 'OTHER') {
      courseName = $('#s_course').val(); 
    }
    $.ajax({
      url: drupalSettings.path.baseUrl + "ajax/user-register",
      type:'POST',
      dataType: "json",
      data: {
        eMail       : $('#s_emailId').val(),
        fName       : $('#s_firstName').val(),
        lName       : $('#s_lastName').val(),
        password    : $.trim($('#studentFrm .ppassword').val()),
        c_password  : $.trim($('#studentFrm .cPassword').val()),
        gender      : $('#studentFrm input[name=gender]:checked').val(),
        dob         : $('#s_dobReg').val(),
        mobile      : $('#s_cCode').val() +'-'+$('#s_mobNo').val(),
        courseType  : $('#s_courseType :selected').text(),
        courseName  : courseName,
        joinYr      : $('#s_joinYear :selected').text(),
        batchNo     : $('#s_batch').val(),
        rollNo      : $('#s_rollNo').val(),
        fid         : $('#tokenfid').val(),
        userType    : 'student',
        token       : $('#csrftoken').val()
      },
      success: function(response) {
        $('#csrftoken').val(response.tok);
        if(response.data == 'success') {
          $('.formFealds').remove();
          $('.innerContainer').html('<div class="messages messages--status">'+ response.msg +'</div>');
          $('.formPage').find('h3').text('Account Created Successfully');
        } else {
          for(var x in response.msg) {
            var temp    = response.msg[x].split('-');
            var fieldId = temp[0];
            var mesg    = temp[1];
            if(fieldId == 'formFealds') {
              $('.formFealds').prepend('<div class="messages messages--error">'+ mesg +'</div>');
              $(window).scrollTop($(".messages:visible").offset().top-125);
            } else {
              $('#studentFrm .'+ fieldId).after('<span class="errorMsg">'+ mesg +'</span>');
            }
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-200);
        }
      }
    });
});
  
  $(document).on('click', '.facultyReg', function() {
    $('.errorMsg').remove();
    var courseName = '';
    $.ajax({
      url: drupalSettings.path.baseUrl + "ajax/user-register",
      type:'POST',
      dataType: "json",
      data: {
        eMail       : $('#f_emailId').val(),
        fName       : $('#f_firstName').val(),
        lName       : $('#f_lastName').val(),
        password    : $.trim($('#facultyFrm .ppassword').val()),
        c_password  : $.trim($('#facultyFrm .cPassword').val()),
        gender      : $('#facultyFrm input[name=gender]:checked').val(),
        dob         : $('#f_dobReg').val(),
        mobile      : $('#f_cCode').val() + '-' + $('#f_mobNo').val(),
        areaGroup   : $("#areaGrp").val(),
        subGroup    : $("#subGrp").val(),
        experience  : $('#expFactGrp').val(),
        fid         : $('#tokenfid').val(),
        userType    : 'faculty',
        token       : $('#csrftoken').val()
      },
      success: function(response) {
        $('#csrftoken').val(response.tok);
        if(response.data == 'success') {
          $('.formFealds').remove();
          $('.innerContainer').html('<div class="messages messages--status">'+ response.msg +'</div>');
          $('.formPage').find('h3').text('Account Created Successfully');
        } else {
          for(var x in response.msg) {
            var temp    = response.msg[x].split('-');
            var fieldId = temp[0];
            var mesg    = temp[1];
            if(fieldId == 'formFealds') {
              $('.formFealds').prepend('<div class="messages messages--error">'+ mesg +'</div>');
              $(window).scrollTop($(".messages:visible").offset().top-125);
            } else {
              $('#facultyFrm .'+ fieldId).after('<span class="errorMsg">'+ mesg +'</span>');
            }
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-200);
        }
      }
    });
  });
  
  $(document).on('click', '.nextToBtn', function() {
    $('.errorMsg').remove();
    if($(this).hasClass('faculty'))  { var userType = 'faculty'; }
    if($(this).hasClass('student'))  { var userType = 'student'; }
    if($(this).hasClass('alumni'))   { var userType = 'alumni'; }
    if(userType == 'alumni' || userType == 'student') {
      var joinYr  = $.trim($('.joiningYear option:selected').text());
      var batchNo = $.trim($('.batchNo option:selected').text());
      var rollNo  = $.trim($('.rollNo').val());
      var courseType = $.trim($('.courseType option:selected').text());
      if(courseType == 'OTHER') {
        var courseName = $.trim($('.courseName').val());
      } else {
        var courseName = '';
      }
    } else {
      var joinYr      = '';
      var batchNo     = '';
      var rollNo      = '';
      var courseName  = '';
      var batchNo     = '';
      var rollNo      = '';
    }
    if(userType == 'alumni') {
      if($('.addrChk').is(':checked')) {
        var addrChked = '1';
      } else {
        var addrChked = '0';
      }
      var addr1     = $.trim($('.addr1').val());
      var addr2     = $.trim($('.addr2').val());
      var addr3     = $.trim($('.addr3').val());
      var cur_cntry = $.trim($('.countries option:selected').text());
      var cur_state = $.trim($('.states option:selected').text());
      var cur_city  = $.trim($('.cityList option:selected').text());
      var gradYr    = $.trim($('.graduatingYear option:selected').text());
      if($('.addrChk').is(':checked')) {
        var pAddr1 = $.trim(addr1);
        var pAddr2 = $.trim(addr2);
        var pAddr3 = $.trim(addr3);
        var pCntry = $.trim(cur_cntry);
        var pState = $.trim(cur_state);
        var pCity  = $.trim(cur_city);
      } else {
        var pAddr1 = $.trim($('.perAddr1').val());
        var pAddr2 = $.trim($('.perAddr2').val());
        var pAddr3 = $.trim($('.perAddr3').val());
        var pCntry = $.trim($('.permCntry option:selected').text());
        var pState = $.trim($('.permState option:selected').text());
        var pCity  = $.trim($('.permCity option:selected').text());
      }
      tempArray   = [];
      $('.familySec .makeCloneIt').each(function() {
        var famName     = $.trim($(this).find('.famName').val());
        var famRelation = $.trim($(this).find('.famRelation').val());
        var famAge      = $.trim($(this).find('.famAge').val());
        var famContact  = $.trim($(this).find('.famContact').val());
        var famData = {
          'name' : famName,
          'relation' : famRelation,
          'age' : famAge,
          'mobile' : famContact
        };
        tempArray.push(famData);
      });
      var famDetails = JSON.stringify(tempArray);
    } else {
      var gradYr      = '';
      var famDetails  = '';
    }
    if(userType == 'faculty') {
      var areaGrp   = $.trim($('.areaGrp option:selected').text());
      var subjGrp   = $.trim($('.subjGrp option:selected').text());
      var irmaExp   = $.trim($('.expfact').val());
    } else {
      var areaGrp   = '';
      var subjGrp   = '';
      var irmaExp   = '';
    }
    
    
    $.ajax({
      url: "/ajax/user-account",
      type:'POST',
      dataType: "json",
      data: {
        'userType'        : userType,
        'userId'          : $('.profileImg').eq(0).attr('id'),
        'fname'           : $.trim($('.fName').val()),
        'lname'           : $.trim($('.lName').val()),
        'email'           : $.trim($('.mail').val()),
        'dob'             : $.trim($('.dob').val()),
        'gender'          : $.trim($('input[name=gender]:checked').val()),
        'code'            : $.trim($('.telCode').val()),  
        'mobile'          : $.trim($('.mobileNo').val()),
        'nickName'        : $.trim($('.nickName').val()),
        'hobbies'         : $.trim($('.hobbies').val()),
        'funFotoId'       : $.trim($('.fntoken').val()),
        'addrChked'       : addrChked,
        'addr1'           : addr1,
        'addr2'           : addr2,
        'addr3'           : addr3,
        'cntry'           : cur_cntry,
        'state'           : cur_state,
        'city'            : cur_city,
        'courseType'      : courseType,
        'courseName'      : courseName,
        'joiningYear'     : joinYr,
        'graduatingYear'  : gradYr,
        'batchNo'         : batchNo,
        'rollNo'          : rollNo,
        'pAddr1'          : pAddr1,
        'pAddr2'          : pAddr2,
        'pAddr3'          : pAddr3,
        'addrChk'         : $(".addrChk").is(":checked"),
        'pCntry'          : pCntry,
        'pCity'           : pCity,
        'pState'          : pState,
        'famDetails'      : famDetails,
        'areaGrp'         : areaGrp,
        'experience'      : irmaExp,
        'subjGrp'         : subjGrp,
        'acctType'        : 'basic',
        'fidToken'        : $('#fidtoken').val(),
        'fntoken'         : $('#fntoken').val(),
        'token'           : $('#csrftoken').val()
      },
      success: function(response) {
        $('#csrftoken').val(response.tok);
        if(response.status == 'success') {
          if(response.red != '') {
            $(".tabAcc2 .tabSec li").eq(1).addClass('active').siblings().removeClass('active');
            $('.tabAcc2 .tabContent .contentDiv').eq(1).show().siblings().hide();
            $('html, body').animate({
                 scrollTop:$('#job-details .left-col').offset().top
            }, 500);
          }
        } else {
          for(var x in response.mesg) {
            var temp    = response.mesg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            if(errID.indexOf('*') > 0) {
              var a = errID.split('*');
              $('.'+a[0]).eq(a[1]).after('<span class="errorMsg">'+ errMsg +'</span>');
            } else {
              $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
            }
            $(window).scrollTop($(".errorMsg:visible").offset().top-125);
          }
        }
      }
    });
  });
  
  $(document).on('click', '.alumni_update, .faculty_update, .student_update', function() {
    $('.errorMsg').remove();
    if($(this).hasClass('faculty_update'))  { var userType = 'faculty'; }
    if($(this).hasClass('student_update'))  { var userType = 'student'; }
    if($(this).hasClass('alumni_update'))   { var userType = 'alumni'; }

    if(userType == 'alumni') {
      var tempArray   = [];
      $('.AchvAwrds .makeCloneIt').each(function() {
        var awardName = $.trim($(this).find('.awardName').val());
        var awardUrl  = $.trim($(this).find('.awardUrl').val());
        var awarDesc  = $.trim($(this).find('.awarDesc').val());
        var awardData = {
          'name'  : awardName,
          'url'   : awardUrl,
          'desc'  : awarDesc
        };
        tempArray.push(awardData);
      });
      var awardsAchv = JSON.stringify(tempArray);
      tempArray   = [];
      $('.mediaCoverage .makeCloneIt').each(function() {
        var mediaName = $.trim($(this).find('.mediaName').val());
        var mediaUrl  = $.trim($(this).find('.mediaUrl').val());
        var mediaDesc = $.trim($(this).find('.mediaDesc').val());
        var mediaData = {
          'name'  : mediaName,
          'url'   : mediaUrl,
          'desc'  : mediaDesc
        };
        tempArray.push(mediaData);
      });
      var mediaCoverage = JSON.stringify(tempArray);
      tempArray   = [];
      $('.eduBkGrnd .makeCloneIt').each(function() {
        var qualfn      = $.trim($(this).find('.qualfn').val());
        var instution   = $.trim($(this).find('.instution').val());
        var passYr      = $.trim($(this).find('.yrPassing option:selected').text());
        var eduData = {
          'qualification' : qualfn,
          'institution'   : instution,
          'yearPassing'   : passYr
        };
        tempArray.push(eduData);
      });
      var eduQualification = JSON.stringify(tempArray);
      tempArray   = [];
      $('.xPriance .makeCloneIt').each(function() {
        var designation     = $.trim($(this).find('.design').val());
        var organisation    = $.trim($(this).find('.organisation option:selected').text());
        var industry        = $.trim($(this).find('.industry option:selected').text());
        var country         = $.trim($(this).find('.XprienceCntry option:selected').text());
        var state           = $.trim($(this).find('.XprienceState option:selected').text());
        var city            = $.trim($(this).find('.XprienceCity option:selected').text());
        var from            = $.trim($(this).find('.empFrom').val());
        var to              = $.trim($(this).find('.empTo').val());
        var scope           = $.trim($(this).find('.scope').text());
        var workHereChk     = $.trim($(this).find('.input[name=workChk]').is(':checked'));
        var xPrianceData = {
          'designation'   : designation,
          'organisation'  : organisation,
          'industry'      : industry,
          'country'       : country,
          'state'         : state,
          'city'          : city,
          'from'          : from,
          'to'            : to,
          'scope'         : scope,
          'workHereChk'   : workHereChk
        };
        tempArray.push(xPrianceData);
      });
      var xPriance = JSON.stringify(tempArray);
      
    } else {
      var awardsAchv        = '';
      var mediaCoverage     = '';
      var eduQualification  = '';
      var xPriance          = '';
    }
    
    if(userType == 'student') {
      var numWrkExp   = $.trim($('.yrsExpr option:selected').text());
      var lastSector  = $.trim($('.sectorWrkd option:selected').text());
    } else {
      var numWrkExp   = '';
      var lastSector  = '';
    }
    if(userType == 'faculty') {
      var totalExp  = $.trim($('.totalExp option:selected').text());
    } else {
      var totalExp  = '';
    }
    
    $.ajax({
      url: "/ajax/user-account",
      type:'POST',
      dataType: "json",
      data: {
        'userType'        : userType,
        'userId'          : $('.profileImg').eq(0).attr('id'),
        'awardsAchv'      : awardsAchv,
        'mediaCoverage'   : mediaCoverage,
        'eduQualification': eduQualification,
        'xPriance'        : xPriance,
        'numWrkExp'       : numWrkExp,
        'lastSector'      : lastSector,
        'totalExp'        : totalExp,
        'acctType'        : 'proff',
        'cvtoken'         : $('#cvtoken').val(),
        'linkdinUrl'      : $('.linkedUrl').val(),
        'token'           : $('#csrftoken').val()
      },
      success: function(response) {
        $('#csrftoken').val(response.tok);
        if(response.status == 'success') {
          if(response.red != '') {
            window.location = response.red;
          }
        } else {
          for(var x in response.mesg) {
            var temp    = response.mesg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            if(errID.indexOf('*') > 0) {
              var a = errID.split('*');
              $('.'+a[0]).eq(a[1]).after('<span class="errorMsg">'+ errMsg +'</span>');
            } else {
              $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
            }
          }
          if($(".errorMsg").is(":visible")) {
            $(window).scrollTop($(".errorMsg:visible").offset().top-125);
          }
          
        }
      }
    });
  });
 
  $(document).on('click', '.projApply', function() {
    $('.messages').remove();
    $('.errorMsg').remove();
    if($('.facultyContact').val() == '' || $('.facultyContact').val() == 'Concerned IRMA Faculty To Be Contacted') {
      var facultyContact = '';
    } else {
      var facultyContact = $('.facultyContact').val();
    }
    $.ajax({
      url: "/ajax/collaborate-project-apply",
      type:'POST',
      dataType: "json",
      data: {
        'token'           : $('#csrftoken').val(),
        'usr_data'        : $('.formFealds').attr('rel'),
        'fname'           : $('.fName').val(),
        'lname'           : $('.lName').val(),
        'batch'           : $('.batchNo option:selected').text(),
        'org'             : $('.organisation option:selected').text(),
        'design'          : $('.design').val(),
        'email'           : $('.email').val(),
        'cntry'           : $('.country option:selected').text(),
        'state'           : $('.states option:selected').text(),
        'city'            : $('.cityList option:selected').text(),
        'code'            : $('.telCode').val(),  
        'mobile'          : $('.mobileNo').val(),
        'dmName'          : $('.dmName').val(),
        'dmEmail'         : $('.dmEmail').val(),
        'dmContactNo'     : $('.dmContactNo').val(),
        'projBrief'       : $('.projBrief').val(),
        'subjGrp'         : $('.subjGrp option:selected').text(),
        'facultyContact'  : facultyContact,
        'projStart'       : $('.projStart').val(),
        'projEnd'         : $('.projEnd').val(),
      },
      success: function(response) {
        if(response.status == 'success') {
          $('.formFealds').remove();
          $('.accountInfoSec').after('<div class="messages messages--status">'+ response.msg +'</div>');
        } else {
          for(var x in response.msg) {
            var temp    = response.msg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
        $('#csrftoken').val(response.tok);
      }
    });
  });

  $(document).on('click', '.sessApply', function() {
    $('.messages').remove();
    $('.errorMsg').remove();

    $.ajax({
      url: "/ajax/classroom-session-apply",
      type:'POST',
      dataType: "json",
      data: {
        'token'           : $('#csrftoken').val(),
        'usr_data'        : $('.formFealds').attr('rel'),
        'fname'           : $('.fName').val(),
        'lname'           : $('.lName').val(),
        'batch'           : $('.batchNo option:selected').text(),
        'org'             : $('.organisation option:selected').text(),
        'design'          : $('.design').val(),
        'email'           : $('.email').val(),
        'cntry'           : $('.country option:selected').text(),
        'state'           : $('.states option:selected').text(),
        'city'            : $('.cityList option:selected').text(),
        'code'            : $('.telCode').val(),  
        'mobile'          : $('.mobileNo').val(),
        'sessBrief'       : $('.sessBrief').val(),
        'subjGrp'         : $('.subjGrp option:selected').text(),
        'sessHrs'         : $('.sessHrs').val(),
      },
      success: function(response) {
        if(response.status == 'success') {
          $('.formFealds').remove();
          $('.accountInfoSec').html('<div class="messages messages--status">'+ response.msg +'</div>');
        } else {
          for(var x in response.msg) {
            var temp    = response.msg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
        $('#csrftoken').val(response.tok);
      }
    });
  });
  
  $(document).on('click', '.workShopApply', function() {
    $('.messages').remove();
    $('.errorMsg').remove();
    if($('.facultyContact').val() == '' || $('.facultyContact').val() == 'Concerned IRMA Faculty To Be Contacted') {
      var facultyContact = '';
    } else {
      var facultyContact = $('.facultyContact').val();
    }
    $.ajax({
      url: "/ajax/invite-faculty-apply",
      type:'POST',
      dataType: "json",
      data: {
        'token'           : $('#csrftoken').val(),
        'usr_data'        : $('.formFealds').attr('rel'),
        'fname'           : $('.fName').val(),
        'lname'           : $('.lName').val(),
        'batch'           : $('.batchNo option:selected').text(),
        'org'             : $('.organisation option:selected').text(),
        'design'          : $('.design').val(),
        'email'           : $('.email').val(),
        'cntry'           : $('.country option:selected').text(),
        'state'           : $('.states option:selected').text(),
        'city'            : $('.cityList option:selected').text(),
        'code'            : $('.telCode').val(),  
        'mobile'          : $('.mobileNo').val(),
        'dmName'          : $('.dmName').val(),
        'dmEmail'         : $('.dmEmail').val(),
        'dmContactNo'     : $('.dmContactNo').val(),
        'workBrief'       : $('.workBrief').val(),
        'subjGrp'         : $('.subjGrp option:selected').text(),
        'facultyContact'  : facultyContact,
        'workStart'       : $('.workStart').val(),
        'workEnd'         : $('.workEnd').val(),
      },
      success: function(response) {
        if(response.status == 'success') {
          $('.formFealds').remove();
          $('.accountInfoSec').html('<div class="messages messages--status">'+ response.msg +'</div>');
        } else {
          for(var x in response.msg) {
            var temp    = response.msg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
        $('#csrftoken').val(response.tok);
      }
    });
  });
  
  $(document).on('click', '.caStudyApply', function() {
    $('.messages').remove();
    $('.errorMsg').remove();
    if($('.facultyContact').val() == '' || $('.facultyContact').val() == 'Concerned IRMA Faculty To Be Contacted') {
      var facultyContact = '';
    } else {
      var facultyContact = $('.facultyContact').val();
    }
    $.ajax({
      url: "/ajax/codevelop-case-study-apply",
      type:'POST',
      dataType: "json",
      data: {
        'token'           : $('#csrftoken').val(),
        'usr_data'        : $('.formFealds').attr('rel'),
        'fname'           : $('.fName').val(),
        'lname'           : $('.lName').val(),
        'batch'           : $('.batchNo option:selected').text(),
        'org'             : $('.organisation option:selected').text(),
        'design'          : $('.design').val(),
        'email'           : $('.email').val(),
        'cntry'           : $('.country option:selected').text(),
        'state'           : $('.states option:selected').text(),
        'city'            : $('.cityList option:selected').text(),
        'code'            : $('.telCode').val(),  
        'mobile'          : $('.mobileNo').val(),
        'dmName'          : $('.dmName').val(),
        'dmEmail'         : $('.dmEmail').val(),
        'dmContactNo'     : $('.dmContactNo').val(),
        'csBrief'         : $('.csBrief').val(),
        'subjGrp'         : $('.subjGrp option:selected').text(),
        'facultyContact'  : facultyContact,
        'csStart'         : $('.csStart').val(),
        'csEnd'           : $('.csEnd').val(),
      },
      success: function(response) {
        $('#csrftoken').val(response.tok);
        if(response.status == 'success') {
          $('.formFealds').remove();
          $('.accountInfoSec').html('<div class="messages messages--status">'+ response.msg +'</div>');
        } else {
          for(var x in response.msg) {
            var temp    = response.msg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
      }
    });
  });
  
  $(document).on('click', '.refRecruitApply', function() {
    $('.messages').remove();
    $('.errorMsg').remove();
    $.ajax({
      url: "/ajax/refer-recruiter-apply",
      type:'POST',
      dataType: "json",
      data: {
        'token'           : $('#csrftoken').val(),
        'usr_data'        : $('.formFealds').attr('rel'),
        'fname'           : $('.fName').val(),
        'lname'           : $('.lName').val(),
        'batch'           : $('.batchNo option:selected').text(),
        'org'             : $('.organisation option:selected').text(),
        'design'          : $('.design').val(),
        'email'           : $('.email').val(),
        'cntry'           : $('.country option:selected').text(),
        'state'           : $('.states option:selected').text(),
        'city'            : $('.cityList option:selected').text(),
        'code'            : $('.telCode').val(),  
        'mobile'          : $('.mobileNo').val(),
        'hrName'          : $('.hrName').val(),
        'dmName'          : $('.dmName').val(),
        'dmEmail'         : $('.dmEmail').val(),
        'dmContactNo'     : $('.dmContactNo').val(),
        'recMnth'         : $('.recMnth option:selected').text(),
        'otherDtls'       : $('.otherDtls').val(),
      },
      success: function(response) {
        $('#csrftoken').val(response.tok);
        if(response.status == 'success') {
          $('.formFealds').remove();
          $('.accountInfoSec').html('<div class="messages messages--status">'+ response.msg +'</div>');
        } else {
          for(var x in response.msg) {
            var temp    = response.msg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
      }
    });
  });
  
  $(document).on('click', '.irmaLogin', function() {
     //alert($(location).attr('href'));
     //return false;
    $('.loginDivSec .errorMsg').remove();
    $.ajax({
      url: "/ajax/user-login",
      type:'POST',
      dataType: "json",
      data: {
        'csrf'      : $('#csrfToken').val(),
        'random'    : $('#rndToken').val(),
        'email'     : $(this).closest('.loginDivSec').find('.emailLgn').val(),
        'ref'       : $(location).attr('href'),
        'password'  : $(this).closest('.loginDivSec').find('.passwordLgn').val()
      },
      
     
      
      success: function(response) {
        $('#csrfToken').val(response.tok);
        $('#rndToken').val(response.rndTok);
        if(response.status == 'success') {
          $('.flip-container').remove();
          window.location = response.redUrl;
        } else {
          //if(response.msg.length > 1) {
            for(var x in response.msg) {
              var temp    = response.msg[x].split("-");
              var errID   = temp[0];
              var errMsg  = temp[1];
              if(errID == 'confrm') {
                $('.loginDivSec .innerWrapper').html('<p style="padding-top:30px;">'+errMsg+'<br><br>'+temp[2]+'</p>');
              } else {
                $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
              }
            }
          /*} else {
            $('.loginDivSec').before('<span class="errorMsg">'+ errMsg +'</span>');
          }*/
        }
      }
    });
  });
  
  $(document).on('change', '.sortYear', function() {
    if($('body').hasClass('page-alumni-network-events-archived-events')) {
      var type = 'archived';
    } else {
      var type = 'upcoming';
    }
    var evtYear = $(this).val();
    if($('sortEvnt').val() != 'Select') {
      var venue = $('sortEvnt').val();
    } else {
      var venue = '';
    }
    
    if($('sortYear').val() == 'Select' && $('sortEvnt').val() == 'Select') {
      return false;
    }
    
    $.ajax({
      url: "/ajax/sort-events",
      type:'POST',
      dataType: "json",
      data: {'type' : type, 'venue' : venue,'evtYear' : evtYear },
      success: function(response) {
        console.log(response.data);
        if(response.data != '') {
          $('.alList').html('');
          $('.alList').html(response.data);
        }
        if(response.loadMore == 0) {
          $('.eventsLoadMore').remove();
        }

      }
    });
  
  });
  
  $(document).on('change', '.sortEvnt', function() {
    if($('body').hasClass('page-alumni-network-events-archived-events')) {
      var type = 'archived';
    } else {
      var type = 'upcoming';
    }
    var venue = $(this).val();
    if($('sortYear').val() != 'Select') {
      var evtYear = $('sortYear').val();
    } else {
      var evtYear = '';
    }
    if($('sortYear').val() == 'Select' && $('sortEvnt').val() == 'Select') {
      return false;
    }
    
    $.ajax({
      url: "/ajax/sort-events",
      type:'POST',
      dataType: "json",
      data: {'type' : type, 'venue' : venue,'evtYear' : evtYear },
      success: function(response) {
        console.log(response.data);
        if(response.data != '') {
          $('.alList').html('');
          $('.alList').html(response.data);
        }
        if(response.loadMore == 0) {
          $('.eventsLoadMore').remove();
        }
      }
    });
  
  });
  
  $(document).on('click', '.loadMoreAL', function() {
    if($('body').hasClass('page-alumni-network-join-the-network-alumni-listing')) {
      var type = 'alumni';
    }
    if($('body').hasClass('page-alumni-network-join-the-network-faculty-listing')) {
      var type = 'faculty';
    }
    if($('body').hasClass('page-alumni-network-join-the-network-student-listing')) {
      var type = 'student';
    }
    var nid = $('.alList li').last().attr('rel');
    $('.messages').remove();
    $.ajax({
      url: "/ajax/listing-more",
      type:'POST',
      dataType: "json",
      data: {
        'nid'   : nid,
        'type'  : type
     },
      success: function(response) {
        if(response.data == '') {
          $('#block-alumnilisting').after('<span class="messages">No Result found</span>');
        } else {
          $('.alList').append(response.data);
        }
        if(response.loadMore == '0') {
          $('.loadMoreAL').remove();
        }
      }
    });
  });
  
  $(document).on('click', '.alumListSrchBtn', function() {
    $('.messages').remove();
    var city      = $('#allCities').val();
    var org       = '';
    var ind       = '';
    var batchNum  = '';
    var gradYear  = '';
    
    if($.trim($('.name').val()) == 'Enter Name' || $.trim($('.name').val()) == '') {
      var name = '';
    } else {
      var name = $('.name').val();
    }
    
    if($('.alumGradYr option:selected').text() == 'Batch Graduating Year') {
      var gradYear = '';
    } else {
      var gradYear = $('.alumGradYr option:selected').text();
    }
    
    if($('#allCities').val() == '' || $('#allCities').val() == 'City') {
      var city = '';
    } else {
      city = $('#allCities').val();
    }

    first = true;
    $('.org .chosen-choices li').each(function() {
      if($(this).hasClass('search-choice')) {
        if(first) {
          org +=  $(this).text();
          first = false;
        } else {
          org += "," + $(this).text();
        }
      }
    });
    first = true;
    $('.ind .chosen-choices li').each(function() {
      if($(this).hasClass('search-choice')) {
        if(first) {
          ind += $(this).text();
          first = false;
        } else {
          ind += ',' + $(this).text();
        }
      }
    });
    first = true;
    $('.batchNum .chosen-choices li').each(function() {
      if($(this).hasClass('search-choice')) {
        if(first) {
          batchNum += $(this).text();
          first = false;
        } else {
          batchNum += ',' + $(this).text();
        }
      }
    });

    if(name == '' && city == '' && org == '' && ind == '' && batchNum  == '' && gradYear == '') {
      return false;
    }
    $.ajax({
      url: "/ajax/alumni-search",
      type:'POST',
      dataType: "json",
      data: {
        'type'    : 'alumni',
        'name'    : name,
        'city'    : $.trim(city),
        'org'     : org,
        'ind'     : ind,
        'batchNum': batchNum,
        'gradYear': gradYear
      },
      success: function(response) {
        $('.loadMoreAL').remove();
        if(response.data == '') {
          $('.alList').after('<div class="messages messages-status">No Result found</div>');
          $('.alList').html('');
        } else {
          $('.mainListingP ul').html('');
          $('.mainListingP ul').html(response.data);
        }
      }
    });
  });
  
  $(document).on('click', '.factListSrchBtn', function() {
    $('.messages').remove();
    var areaGrp     = '';
    var subjectGrp  = '';
    
    if($.trim($('.name').val()) == 'Enter Name' || $.trim($('.name').val()) == '') {
      var name = '';
    } else {
      var name = $('.name').val();
    }
    
    first = true;
    $('.subGrp .chosen-choices li').each(function() {
      if($(this).hasClass('search-choice')) {
        if(first) {
          subjectGrp += $(this).text();
          first = false;
        } else {
          subjectGrp += ',' + $(this).text();
        }
      }
    });
    
    first = true;
    $('.areaGrp .chosen-choices li').each(function() {
      if($(this).hasClass('search-choice')) {
        if(first) {
          areaGrp +=  $(this).text();
          first = false;
        } else {
          areaGrp += "," + $(this).text();
        }
      }
    });

    if(name == '' && subjectGrp == '' && areaGrp == '') {
      return false;
    }
     
    $.ajax({
      url: "/ajax/faculty-search",
      type:'POST',
      dataType: "json",
      data: {
        'name'       : name,
        'areaGrp'    : areaGrp,
        'subjectGrp' : subjectGrp
      },
      success: function(response) {
        $('.loadMoreAL').remove();
        if(response.data == '') {
          $('.alList').after('<div class="messages messages-status">No Result found</div>');
          $('.alList').html('');
        } else {
          $('.mainListingP ul').html('');
          $('.mainListingP ul').html(response.data);
        }
      }
    });
  });
  
  $(document).on('click', '.studListSrchBtn', function() {
    $('.messages').remove();
    var sector    = '';
    var batchNum  = '';
    
    if($.trim($('.name').val()) == 'Enter Name' || $.trim($('.name').val()) == '') {
      var name = '';
    } else {
      var name = $('.name').val();
    }
    if($.trim($('.workX option:selected').text()) == 'Select Work experience' || $.trim($('.workX option:selected').text()) == '') {
      var workExp = '';
    } else {
      var workExp = $('.workX option:selected').text();
    }
    
    first = true;
    $('.batchNum .chosen-choices li').each(function() {
      if($(this).hasClass('search-choice')) {
        if(first) {
          batchNum += $(this).text();
          first = false;
        } else {
          batchNum += ',' + $(this).text();
        }
      }
    });
    
    first = true;
    $('.sector .chosen-choices li').each(function() {
      if($(this).hasClass('search-choice')) {
        if(first) {
          sector +=  $(this).text();
          first = false;
        } else {
          sector += "," + $(this).text();
        }
      }
    });

    if(name == '' && workExp == '' && batchNum == '' && sector == '') {
      return false;
    }
    $.ajax({
      url: "/ajax/student-search",
      type:'POST',
      dataType: "json",
      data: {
        'type'    : 'student',
        'name'    : name,
        'workExp' : workExp,
        'sector'  : sector,
        'batchNum': batchNum
      },
      success: function(response) {
        $('.loadMoreAL').remove();
        if(response.data == '') {
          $('.alList').after('<div class="messages messages-status">No Result found</div>');
          $('.alList').html('');
        } else {
          $('.mainListingP ul').html('');
          $('.mainListingP ul').html(response.data);
        }
      }
    });
  });
  
  $(document).on('click', '.newsSubsBtn', function() {
    $('.errorMsg').remove();
    $.ajax({
      url: "/ajax/subscribe",
      type:'POST',
      dataType: "json",
      data: {'email' : $('.subsNL').val(), 'token' : $('#csrfToken').val() },
      success: function(response) {
        if(response.status == 'success') {
          $('.subsNL').before('<div class="errorMsg">You have successfully subscribed to network.</div>');
          $('.subsNL').val('yourid@mail.com');
        } else {
          $('.subsNL').before('<div class="errorMsg">' + response.mesg + '</div>');
        }
      }
    });
  });
  
  $('input.purpose').closest('li').hide();
  $('input[name=purpVisit]').on('change', function() {
     if($('input[name=purpVisit]:checked').val()=='Official'){
      $('input.purpose').closest('li').show();
     } else {
      $('input.purpose').closest('li').hide();
     } 
  });
  
  $(document).on('click', '.chngPassword', function() {
    $('.errorMsg').remove();
    $.ajax({
      url: "/ajax/change-password",
      dataType: "json",
      type: "POST",
      data:  {
        'old_pass'  : $('.oldPass').val(),
        'new_pass'  : $('.newPass').val(),
        'con_pass'  : $('.confirmPass').val(),
        'token'     : $('#csrftoken').val()
      },
      success: function(data){
        if(data.status == 'success') {
          $('.changePwdP').html('');
          $('.changePwdP').after('<div class="messages messages--status">'+ data.mesg +'</div>');
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        } else {
          $('#csrftoken').val(data.tok);
          for(var x in data.mesg) {
            var temp    = data.mesg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
      },	        
    });
  });
  
  $(document).on('click', '.frgtPassword', function() {
    $('.errorMsg').remove();
    $.ajax({
      url: "/ajax/forgot-password",
      dataType: "json",
      type: "POST",
      data:  {
        'email'  : $('.fpEmail').val(),
        'token'  : $('#csrftoken').val()
      },
      success: function(data){
        if(data.status == 'success') {
          $('.changePwdP').html('');
          $('.changePwdP').after('<div class="messages messages--status">'+ data.mesg +'</div>');
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        } else {
          $('#csrftoken').val(data.tok);
          for(var x in data.mesg) {
            var temp    = data.mesg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        }
      },	        
    });
  });
  
  $(document).on('click', '.rstPassword', function() {
    $('.errorMsg').remove();
    $.ajax({
      url: "/ajax/reset-password",
      dataType: "json",
      type: "POST",
      data:  {
        'newPass' : $('.newPass').val(),
        'conPass' : $('.confirmPass').val(),
        'cToken'  : $('#csrftoken').val(),
        'token'   : $('#token').val(),
      },
      success: function(data){
        if(data.status == 'success') {
          $('.changePwdP').html('');
          $('.changePwdP').after('<div class="messages messages--status">'+ data.mesg +'</div>');
          $(window).scrollTop($(".errorMsg:visible").offset().top-125);
        } else {
          $('#csrftoken').val(data.tok);
          for(var x in data.mesg) {
            var temp    = data.mesg[x].split("-");
            var errID   = temp[0];
            var errMsg  = temp[1];
            $('.'+ errID).after('<span class="errorMsg">'+ errMsg +'</span>');
          }
        }
      },	        
    });
  });
  
  
  
})(jQuery);



