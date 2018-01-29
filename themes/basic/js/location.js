(function ($) {
  function ajaxCall() {
    this.send = function(data, url, method, success, type) {
      type = type||'json';
      var successRes = function(data) {
        success(data);
      };
      var errorRes = function(e) {
        //console.log(e);
        //alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
      };
      $.ajax({
        url: url,
        type: method,
        data: data,
        success: successRes,
        error: errorRes,
        dataType: type,
        timeout: 60000
      });
    }
  }

  function locationInfo() {
    var rootUrl = "api.php";
    var call = new ajaxCall();
    this.getCities = function(id) {
      //$(".cities option:gt(0)").remove();
      var url = '/ajax/cities?type=getCities&stateId=' + id;
      var method = "post";
      var data = {};
      $('.cities').find("option:eq(0)").html("Please wait..");
      call.send(data, url, method, function(data) {
        $('.cities').find("option:eq(0)").html("Select City");
        if(data.tp == 1){
          $.each(data['result'], function(key, val) {
            var option = $('<option />');
            option.attr('value', key).text(val);
            if($('.contentDiv').eq(0).is(':visible')) {
              if($('.permState').closest('li').hasClass('clk')) {
                $('.permCity').append(option);
              } else {
                $('.cities').append(option);
              }
            } else {
              $('.XprienceCity').append(option);
            }
            if(!$('body').hasClass('section-my-account')) {
              $('.cities').append(option);;
            }
          });
          $(".cities").prop("disabled",false);
        } else  {
          //alert(data.msg);
        }
      });
    };

    this.getStates = function(id) {
      
      var url = '/ajax/states?type=getStates&countryId=' + id;
      var method = "post";
      var data = {};
      $('.states').find("option:eq(0)").html("Please wait..");
      call.send(data, url, method, function(data) {
        $('.states').find("option:eq(0)").html("Select State");
        if(data.tp == 1){
          $.each(data['result'], function(key, val) {
          var option = $('<option />');
          option.attr('value', key).text(val);
          if($('.contentDiv').eq(0).is(':visible')) {
            if($('.permCntry').closest('li').hasClass('clk')) {
              $('.permState').append(option);
            } else {
              $('.states').append(option);
            }
          } else {
            $('.XprienceState').append(option);
          }
          if(!$('body').hasClass('section-my-account')) {
            $('.states').append(option);
          }
          
          });
          $(".states").prop("disabled",false);
        } else {
          //alert(data.msg);
        }
      }); 
    };

    this.getCountries = function() {
      var url = '/ajax/countries?type=getCountries';
      var method = "post";
      var data = {};
      var options = '';
      $('.countries').find("option:eq(0)").html("Please wait..");
      call.send(data, url, method, function(data) {
        $('.countries').find("option:eq(0)").html("Select Country");
        for(var x in data.result) {
          if($('body').hasClass('page-register')) {
            if(data.result[x].sortname == 'IN') {
              options += '<option id="'+data.result[x].id+'" value="'+ data.result[x].sortname +'" rel="'+ data.result[x].phonecode+'" selected>'+ data.result[x].name +'</option>';
            } else {
              options += '<option id="'+data.result[x].id+'" value="'+ data.result[x].sortname +'" rel="'+ data.result[x].phonecode+'">'+ data.result[x].name +'</option>';
            }
          } else {
            options += '<option id="'+data.result[x].id+'" value="'+ data.result[x].sortname +'" rel="'+ data.result[x].phonecode+'">'+ data.result[x].name +'</option>';
          }
        }
        $('.countries, .XprienceCntry, .permCntry').append(options);
        $('.countries option').each(function() {
          if($(this).is(':selected')) {
            $(this).closest('.customSelect').find('.shortDropLink').text($(this).text());
          }
        });
        $('.regState').removeClass('dnone');
        $('.regCity').removeClass('dnone');
      }); 
    };
  }


  var loc = new locationInfo();
  if($('body').hasClass('page-register')) {
    loc.getCountries();
    loc.getStates(101);
    loc.getCities(1);
  }
  $(document).on("change", ".countries", function(ev) {
    var countryId = $(this).find('option:selected').attr('id');
    $(".states option:gt(0)").remove();
    $(".cities option:gt(0)").remove();
    $('.states').closest('.customSelect').find('.shortDropLink').text('Select');
    $('.cities').closest('.customSelect').find('.shortDropLink').text('Select');
    if(countryId != ''){
      if(typeof countryId == 'undefined') {
        $('.telCode').val('');
      } else {
        $('.telCode').val('+' + $(this).find('option:selected').attr('rel'));
        loc.getStates(countryId);
      }
      
    } else {
      $(".states option:gt(0)").remove();
    }
  });
  $(".states").on("change", function(ev) {
    var stateId = $(this).val();
    $(".cities option:gt(0)").remove();
    $('.cities').closest('.customSelect').find('.shortDropLink').text('Select');
    if(stateId != ''){
      loc.getCities(stateId);
    } else {
      $(".cities option:gt(0)").remove();
    }
  });
  
  $(".XprienceCntry").on("change", function(ev) {
    var countryId = $(this).find('option:selected').attr('id');
    $(".XprienceState option:gt(0)").remove();
    $(".XprienceCity option:gt(0)").remove();
    $('.XprienceState').closest('.customSelect').find('.shortDropLink').text('Select');
    $('.XprienceCity').closest('.customSelect').find('.shortDropLink').text('Select');
    if(countryId != ''){
      loc.getStates(countryId);
    } else {
      $(".states option:gt(0)").remove();
    }
  });
  
  $(".XprienceState").on("change", function(ev) {
    var stateId = $(this).val();
    $(".XprienceCity option:gt(0)").remove();
    $('.XprienceCity').closest('.customSelect').find('.shortDropLink').text('Select');
    if(stateId != ''){
      loc.getCities(stateId);
    } else {
      $(".cities option:gt(0)").remove();
    }
  });
  
  $(".permCntry").on("change", function(ev) {
    $(this).closest('li').addClass('clk');
    var countryId = $(this).find('option:selected').attr('id');
    $(".permState option:gt(0)").remove();
    $(".permCity option:gt(0)").remove();
    $('.permState').closest('.customSelect').find('.shortDropLink').text('Select');
    $('.permCity').closest('.customSelect').find('.shortDropLink').text('Select');
    if(countryId != ''){
      loc.getStates(countryId);
    } else {
      $(".permState option:gt(0)").remove();
    }
    setTimeout(function() {
      $('.permCntry').closest('li').removeClass('clk');
    },1000);
  });
  
  $(".permState").on("change", function(ev) {
    var stateId = $(this).val();
    $(this).closest('li').addClass('clk');
    $(".permCity option:gt(0)").remove();
    $('.permCity').closest('.customSelect').find('.shortDropLink').text('Select');
    if(stateId != ''){
      loc.getCities(stateId);
    } else {
      $(".permCity option:gt(0)").remove();
    }
    setTimeout(function() {
      $('.permState').closest('li').removeClass('clk');
    },1000);
  });
    
})(jQuery);


