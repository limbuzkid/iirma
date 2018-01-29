
function clearText(a) {
    if (a.defaultValue == a.value) {
      a.value = ""
    } else {
      if (a.value == "") {
        a.value = a.defaultValue
      }
    }
	}
(function ($) {
	var wWidth;
	var wHeight;
	function deviceOrDesktop(){
		wWidth = $(window).width();
		wHeight = $(window).height();
		if(wWidth <= 1024){
			$('html').addClass('device').removeClass('desktop');
		}	else	{
			$('html').addClass('desktop').removeClass('device');
		} 
	}

	$(window).resize(function() {
		deviceOrDesktop()
	}); 

//$(document).ready(function() {
    deviceOrDesktop()
    $('.device section.navigation .containt .navigation-list ul li.sublist ul.sublink').find(".sublist").prev('li').children('a').css('border-bottom', 0)
    
    $(".device .navigation-list ul > li.sublist > a").click(function(){
        $(this).toggleClass("active");
        $(this).next().slideToggle();	   
    });
    $(".close-mobile").click(function(){
        $(".navigation, .transfer").hide();
        $(".menu").removeClass("close");
    });			

    $(document).on( "click", ".menu",function() {

        if(wWidth <= 1024){

            $(".transfer").show(); 
                // $(this).text("Close"); 
                //console.log(wHeight); 
            // $('.navigation').outerHeight(wHeight);

        }
        else{
            // $(".menu label").text("Close");
        }

        $(this).find('label').text("Close");  

        if($(this).hasClass("close")){
        //alert("hi");
            $(".transfer").show();
            $(this).find('label').text("Menu"); 

        $(".navigation").fadeOut();	
            $(this).removeClass("close");
            $(".transfer").hide();
        } 

        else {
             $(this).addClass("close");
             $(".navigation").fadeIn();	
          //$(this).find('label').text("Menu");  
        }

    });

    $(".arrow").click(function(){
        var hei = $("header").height(); 
        // alert(hei);
        $("body,html").animate({scrollTop : $(this).offset().top - hei }, 800)
    });


    /*$(".feature .list").mouseover (function() {
        $(this).find(".information").stop(false).animate({"bottom":"0px"});
    });
        $(".feature .list").mouseout (function() {
        $(this).find(".information").stop(false).animate({"bottom":"-190px"});
    });*/

    $(document).on('mouseover', '#featured-alumni .list', function() {
            $(this).find(".information").stop(false).animate({"bottom":"0px"});
        });
        $(document).on('mouseout', '#featured-alumni .list', function() {
            $(this).find(".information").stop(false).animate({"bottom":"-200px"});
    //.delay(1000);
    });
    
    $(document).on('mouseover', '.mangListDetailList ul li',  function() {
        //$(this).find(".information").stop(false).animate({"bottom":"0px"});
        $(this).find(".information").css('bottom', 0)
    });
    $(document).on('mouseout', '.mangListDetailList ul li', function() {
        //$(this).find(".information").stop(false).animate({"bottom":"-213px"});
        $(this).find(".information").removeAttr('style')
    });
     
    $('.banner-carousal').owlCarousel({
        loop:true,
        nav:true,
        navigation : true,
        pagination: true,
        autoplay:true,
        //animateOut: "fadeOut",
        autoplayTimeout:5000,
        // autoplayHoverPause:true,
        responsive:{
        0:{
            items:1,
            //animateOut:false,
            //autoplayTimeout:5000,
        },
        600:{
            items:1,
            //animateOut: false,
            //autoplayTimeout:5000,
        },
        1000:{
            items:1,
            //animateOut: false,
            //autoplayTimeout:5000,
        }
        }
    });
    
    /*new changes*/
    $('.banner-carousal').on("translated.owl.carousel",function(event){
       //alert(event.page.index)
        $('.banner-carousal .owl-item.active').addClass("pw").siblings().removeClass("pw");
    });
    /*new changes*/
		
    $('.eventscroll').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        navigation : true,
        pagination: true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });
    
    $(document).on('click', '.searchSec a', function(){
        $('.autoSearchbox').slideDown();
        $('.transfer').show().addClass('open');
    });
    $(document).on('click','.transfer.open', function(){
        $('.autoSearchbox').slideUp();
        $(this).hide().removeClass('open');
        $('.searchingDiv').hide();
    });
    
    $("#cCode").attr("readonly", true)
    if(!$('body').hasClass('page-register') && !$('body').hasClass('page-alumni-network-alumni-news-alumni-newsletter')) {
        $('.customSelect .dropdownWrap').each(function(){
            var selectedText = $(this).find('select option:selected').text();
            $(this).find('.shortDropLink').text(selectedText);
        });
    }
    
    $(document).on("change", "select",function(){
        var changeSelected = $(this).find('option:selected').text();
        $(this).parent('.dropdownWrap').find('.shortDropLink').text(changeSelected);
    });
    
    $('.goBtn').on('click', function(){
        $('.searchingDiv').show();
    })
    
    $('.mainListPSrch ul li .advancedSearch').click(function(){
        $(this).hide();
        $(this).parents('.mainListPSrch').find('ul').removeClass('adwanceBtn');
    });
    
    /*$(".uploadBtn").each(function(){
        $(this).find("a").click(function(){
            $("input[type='file']").trigger("click");
        })
    })*/
    
    $(document).on("mouseenter", ".alimniListingCls .flip_container2", function(){
        if($(this).find(".flip_back2").length){
            $(this).find(".flip2").addClass("flipThis")
        }else{}
    })
    $(document).on("mouseleave", ".alimniListingCls .flip_container2", function(){
        $(this).find(".flip2").removeClass("flipThis")
    })
    
    
    /*$('.resp-tabs-list li').click(function(){
        $('.text_box').hide();
        $('.text_box').eq($(this).index()).show();
        $(this).addClass('active').siblings().removeClass('active')
    }).eq(0).click();*/
    
    $('.resp-tabs-list li').click(function(){
        var headerHeight = $("#header .logo").height();
        $('html, body').animate({
             scrollTop:$('.fund-tabsec .resp-tabs-list .text_box').eq($(this).index()).offset().top - headerHeight
        }, 1000);
    })
    
    
    
    
    var n = 0;
    $(document).on("click", ".clineBtn", function(){
        var divClone = $(this).prev().clone().addClass("thisClone");;
        //$(this).before("<div class='cloneDivs'></div>")
        $(this).before(divClone)
        divClone.find("input, textarea").val(" ")
        divClone.find("select option").eq(0).attr("selected", "selected");
        divClone.find("select").prev().text("Select");
        if($(".thisClone").find(".removeClone").length == 0){
            divClone.prepend("<a href='javascript:;' class='removeClone'> x </a>")
        }
        n++;
        var prevCheckBoxId = $(this).prev().children("li").find(".customCheckBox input[type='radio']").attr("id");
        //$(this).parents(".thisClone").children("li").find("input[type='radio']").removeAttr("checked")
        $(this).prev().children("li").find(".customCheckBox input").attr("id", prevCheckBoxId + (n))
        $(this).prev().children("li").find(".customCheckBox input").next().attr("for", prevCheckBoxId + (n));
        
        if($(this).prev().find('input:checked').length){
            $(this).prev().prev().find('input').prop("checked",true);
        }
        
    })
    
    $(document).on("click", ".removeClone", function(){
        $(this).parent().remove();
    })
    
    $(".flip-container").parent().children("a").click(function(){
        $(this).parent().children(".flip-container").removeClass('close');
        $(this).parent().toggleClass("openLogin");
        //$(this).parent().children(".flip-container").slideToggle();
        //$(this).parent().children(".flip-container").removeClass('close');
        if(!$(this).parent().hasClass("openLogin")){
        $(this).parent().children(".flip-container").toggleClass('close');
        }
    })
    
    $(".phoneNo input").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
    
    $("#forgotPwd").click(function(){
        $(this).parents(".flip-container").addClass("hover");
    })
    $("#backLogin").click(function(){
        $(this).parents(".flip-container").removeClass("hover");
    })
    
    var title1;
    $(document).on("click", ".managementLest ul li .btnSec .shareBtn", function(){
        $('.managementLest .showSocial').removeClass('showSocial');
        $('.managementLest .social').html(' ');
        
        
        $(this).parents("li").addClass('showSocial');
        
        console.log(title1)
        $(".showSocial .social").socialLinkBuilder({
            print: {
                isUsed: false
            },
            email: {
                isUsed: true,
                mailto: ' '
            },
            tel: {
                isUsed: false,
                tel: '0123456789'
            },
            gplus:{
                isUsed: false
            }
        });
    })
    
    $(document).on("click",".button", function(){
        setTimeout(function(){
        $(".formPage ul li").each(function(){
            if($(this).children(".customSelect").find(".errorMsg").length){
                //$(this).addClass("marginBtm")
            }
            if($(this).children(".customSelect").find(".errorMsg").length == 0){
                $(this).find().parent().addClass("marginBtm2")
            }
        })
        },500)   
    })
    
    
    //loadMoreFunc()
    lightboxPopup()
    fundTab()
    commonTab()
    deviceMob()
    video_gallery()
    //$('.chosen').chosen();
    featuredInfo()
    
    $(".chosen").chosen({
        disable_search_threshold: 10,
        max_selected_options:3,
        no_results_text: "Oops, nothing found!"
    });
    
//});


var windWidth = $(window).width();
$(window).on('resize',function(){
    if(this.resizeTO) clearTimeout(this.resizeTO);
    this.resizeTO = setTimeout(function() {
        $(this).trigger('resizeEnd');
    },500);
});
$(window).bind('resizeEnd', function(){
    if(windWidth!=$(window).width()){
        deviceMob()
        $('#select_sec').remove()
        fundTab()
        video_gallery();
        featuredInfo()
        
        setTimeout(function(){
            $('.scroll-pane').jScrollPane();
        },10)
    }
    windWidth=$(window).width();
});

function featuredInfo(){
    $('.listinfo').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    autoplay:true,
    navigation : true,
    //autoplayHoverPause: true, // Stops autoplay
    pagination: true,
    responsive:{
		0:{
			items:2
		},
		320:{
			items:1
		},
		360:{
			items:1
		},
		480:{
			items:1
		},
		600:{
			items:1
		},
		658:{
			items:2
		},
		667:{
			items:1,
            dots:false,
		},
		768:{
			items:1
		},
		800:{
			items:1
		},
		980:{
			items:2
		},
		1000:{
			items:2
		}
		}
	});
    $('.listinfo').on('mouseenter',function(e){
        $(this).trigger('stop.owl.autoplay');
    })

    $('.listinfo').on('mouseleave',function(e){
        $(this).trigger('play.owl.autoplay');
    })
}
    
function deviceMob(){
    var windowWidth = $(window).width();
    if(windowWidth < 768){
        setTimeout(function(){
            $('html, body').animate({
                 scrollTop:$('#lightBox').offset().top
            }, 1000, function(){
                $('#lightBox').animate({'top': '25px'});
            });
        },10)
        $('body').addClass('mobile')
    }else{
        $('body').removeClass('mobile')
    }
}

function video_gallery(){
	// script written by suraj Start
	 var sync1 = $("#sync1");
  var sync2 = $("#sync2");
  var slidesPerPage = 6; //globaly define number of elements per page
  var syncedSecondary = true;

  sync1.owlCarousel({
    items : 1,
    slideSpeed : 2000,
    autoplay: false,
	nav: false,
    dots: false,
    loop: true,
    responsiveRefreshRate : 200,
   //navText: ['<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>','<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
  }).on('changed.owl.carousel', syncPosition);

  sync2
    .on('initialized.owl.carousel', function () {
      sync2.find(".owl-item").eq(0).addClass("current");
    })
    .owlCarousel({
    items : 6,
    dots: false,
    smartSpeed: 200,
    slideSpeed : 500,
	nav: true,
    //slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
	responsiveRefreshRate : 100,
		responsive:{
			0:{
				items:3
			},
			480:{
				items:3		
			},
			600:{
				items:4
			},
			1000:{
				items:6
			}
		}
  }).on('changed.owl.carousel', syncPosition2);

  function syncPosition(el) {
    //if you set loop to false, you have to restore this next line
    //var current = el.item.index;
    
    //if you disable loop you have to comment this block
    var count = el.item.count-1;
    var current = Math.round(el.item.index - (el.item.count/2) - .5);
    
    if(current < 0){
      current = count;
    }
    if(current > count){
      current = 0;
    }
    
    //end block

    sync2
      .find(".owl-item")
      .removeClass("current")
      .eq(current)
      .addClass("current");
    var onscreen = sync2.find('.owl-item.active').length - 1;
    var start = sync2.find('.owl-item.active').first().index();
    var end = sync2.find('.owl-item.active').last().index();
    
    if (current > end) {
      sync2.data('owl.carousel').to(current, 100, true);
    }
    if (current < start) {
      sync2.data('owl.carousel').to(current - onscreen, 100, true);
    }
  }
  
  function syncPosition2(el) {
    if(syncedSecondary) {
      var number = el.item.index;
      sync1.data('owl.carousel').to(number, 100, true);
    }
  }
  
  sync2.on("click", ".owl-item", function(e){
    e.preventDefault();
    var number = $(this).index();
    sync1.data('owl.carousel').to(number, 300, true);
  });
	
	var slider_height = $("#sync1 img").height()	
	//console.log(slider_height);
	 $("#sync1 iframe").height(slider_height);
	
	//script written by suraj End
	
	
}

function fundTab(){
    $("#sel-option li").eq(0).addClass("active");
	$('#sel-option').removeAttr('style');
	$('.resp-tabs-list').prepend('<a id="select_sec" href="javascript:;"> '+$('#sel-option li.active').text()+' </a>');
	$('#select_sec').click(function(){
		$(this).next().slideToggle(500); 
		$(this).next().toggleClass("active_one");
		$(this).toggleClass("active");
	});
	$('#select_sec').next().children().click(function(){
		if($('body').hasClass('mobile')){
			var tabLiText = $(this).text();
			$('#select_sec').text(tabLiText)
			console.log($(this).text());
			$(this).parent().slideUp();
			$('#sel-option').removeClass("active_one");	
			$(this).parent().removeClass("active");
		}
	});
}

function commonTab(){
    $("#job-details .tabSec.infoTab").parent().addClass("tabAcc2");
    
    $('.tabSec').find('li').click(function(){
        if($(this).parents().hasClass("tabAcc2")){
            var fname           = $('.fName').val();
            var lname           = $('.lName').val();
            var mail            = $('.mail').val();
            var mobileNo        = $('.mobileNo').val();
            if($('.nextToBtnClick').hasClass('alumni')) {
              var country         = $('#countryId option:selected').text();
              var state           = $('#stateId option:selected').text();
              var city            = $('#cityId option:selected').text();
              var courseType      = $('.courseType option:selected').text();
              var joiningYear     = $('.joiningYear option:selected').text();
              var graduatingYear  = $('.graduatingYear option:selected').text();
              var batchNo         = $('.batchNo option:selected').text();
              var famName         = $('.familySec .makeCloneIt li').eq(0).find('input').val();
              if(fname != '' && lname != '' && mail != '' && mobileNo != '' && batchNo != '' && famName != '' && (batchNo != '' || batchNo != 'Select') && (graduatingYear != '' || graduatingYear != 'Select') && (joiningYear != '' || joiningYear != 'Select') && (country != '' || country != 'Select') && (state != '' || state != 'Select') && (city != '' || city != 'Select') && (courseType != '' || courseType != 'Select')) {
                //alert('click');
              } else {
                return false;
              }
            }
            if($('.nextToBtnClick').hasClass('student')) {
              var courseType  = $('.courseType option:selected').text();
              var joiningYear = $('.joiningYear option:selected').text();
              var batchNo     = $('.batchNo option:selected').text();
              if(fname != '' && lname != '' && mail != '' && mobileNo != '' && batchNo != '' && (batchNo != '' || batchNo != 'Select') && (joiningYear != '' || joiningYear != 'Select') && (courseType != '' || courseType != 'Select')) {
                //alert('click');
              } else {
                return false;
              }
            }
            if($('.nextToBtnClick').hasClass('faculty')) {
              var areaGrp = $('.areaGrp option:selected').text();
              var subjGrp = $('.subjGrp option:selected').text();
              var expfact = $('.expfact').val();
              if(fname != '' && lname != '' && mail != '' && mobileNo != '' && (areaGrp != '' || areaGrp != 'Select') && (subjGrp != '' || subjGrp != 'Select') && expfact != '') {
                //alert('click');
              } else {
                return false;
              }
            }
            //return false;
        }
        
        var index = $(this).index();
        $(this).addClass('active').siblings().removeClass('active')
        $('.tabContent').find('.contentDiv').hide();
        $('.tabContent').find('.contentDiv').eq(index).show();
    }).eq(0).click();
    
    
    $(".tabAcc2 .tabSec li").eq(0).addClass('active').siblings().removeClass('active');
    $('.tabAcc2 .tabContent .contentDiv').eq(0).show().siblings().hide();
    
    $(".bachAccClick").click(function(){
        $(".tabAcc2 .tabSec li").eq(0).addClass('active').siblings().removeClass('active');
        $('.tabAcc2 .tabContent .contentDiv').eq(0).show().siblings().hide();
        $('html, body').animate({
             scrollTop:$('#job-details .left-col').offset().top
        }, 500);
        
    })
}

function loadMoreFunc(){
    var ths = $('.loadMoreFunc');
    ths.find('li').addClass('child');
    ths.find('.child').hide();
    var defaultShowItem = parseInt(ths.attr('data-show'))
    var loadItem = parseInt(ths.attr('data-load'))
    
    ths.append('<a class="button loadMoreBtn" href="javascript:;">Load more</a>');
    
    for(var i = 0; i < defaultShowItem; i++){
        ths.find('.child').eq(i).show();
    }
    
    if(ths.find('.child').length <= defaultShowItem){
        ths.find('.loadMoreBtn').hide()
    }
    
    var size_li = ths.find('.child').size();
    
    var x = defaultShowItem;
    ths.find('.loadMoreBtn').click(function(){
        x= (x+loadItem <= size_li) ? x+loadItem : size_li;
        ths.find('.child:lt('+x+')').show();
        if(x == size_li){
            ths.find('.loadMoreBtn').hide()
        }
        
        $('html, body').animate({
             scrollTop:$(ths.find('.loadMoreBtn')).offset().top
        }, 1000);
    });
    
}

function lightboxPopup(){
    $(".clickLightbox").each(function(){
        $(document).on("click", ".clickLightbox", function(){
            $('.transfer').show();
            $('#lightBox').show()
            setTimeout(function(){
                $('.scroll-pane').jScrollPane();
                $('html, body').animate({
                     scrollTop:$('#lightBox').offset().top
                }, 300);
            },200)
            deviceMob()
        });
    });
    $('#close').click(function(){
        $('.transfer').hide();
        $('#lightBox .imgSec').remove();
        $('#lightBox .rightContent').remove();
        $('#lightBox').hide();
    })
}


$(window).load(function(){
	//alert("hi")
	var slider_height = $("#sync1 .item img").height()	
	//console.log(slider_height);
	 $("#sync1 iframe").height(slider_height);
    if($("input[type='radio']").attr("checked")){
        $("input[type='radio']").removeAttr("checked")
    }
});

$(document).ready(function () {
    if(window.location.href.indexOf("/alumni-network") > -1){
       $(".mainNavigation .Alumni a").css({'border-bottom':'4px solid #ffcc00','color':'#ffcc00'});
    }
    else if (window.location.href.indexOf("/irma") > -1){
       $(".mainNavigation .Irma a").css({'border-bottom':'4px solid #ffcc00','color':'#ffcc00'});
    }
});

    /*new changes*/
    $(window).load(function(){
        $("html").removeAttr("id")
        $("html,body").animate({scrollTop:0},10);
    })
    
    function pageSkroller() {
        if ($("[data-scroll='active']").length) {
            var offTop = $("[data-scroll='active']").eq(0).offset().top;
            offTop = $("[data-scroll='active']").offset().top;
            var windowScrollTop = $(window).scrollTop();
            if ((offTop - windowScrollTop >= 0 && offTop - windowScrollTop < $(window).height() / 1.5) || windowScrollTop == 0) {
                //console.log(groupSelector)
                $("[data-scroll='active']").each(function() {
                    $(this).attr("data-scroll", "completed");
                    $("[data-scroll='']").eq(0).attr("data-scroll", "active");
                    
                    /*if($(this).attr("id")=="GrowthSpurt"){

                        var bigCircle = $(".alumDetails");
                        var circles = CSSRulePlugin.getRule(".alumDetails"); 
                        var text = $(".alumDetails");
                        var growthTL = new TimelineLite({
                            onComplete:function(){

                            }
                        });

                        growthTL.from(bigCircle, 0.3, {scale:0, transformOrigin:"50% 50%", ease:Back.easeOut})
                                .from(circles, 0.3, {cssRule:{scale:0, transformOrigin:"50% 50%", ease:Back.easeOut}})
                                .from(text[0], 0.4, {x:-200, opacity:0, ease:Quad.easeOut})
                                .from(text[1], 0.4, {x:-200, opacity:0, ease:Quad.easeOut},"-=0.2")
                                .from(text[2], 0.4, {x:200, opacity:0, ease:Quad.easeOut},"-=0.2")
                                .from(text[3], 0.4, {x:200, opacity:0, ease:Quad.easeOut},"-=0.2")
                    }*/

                });
            }
        }
    }

    $(window).on("scroll",function(){
        pageSkroller();
    })
    /*new changes*/
    
})(jQuery);