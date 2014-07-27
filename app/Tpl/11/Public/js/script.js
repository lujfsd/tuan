var errHideTimeOut;
var userMenuTimeOut;
var ecvTimeOut;
var imgListCurr=0;
var imgListNext=0;
var imgListCount=0;
var imgListInterval;

(function($){

	$.getStringLength=function(str)
	{
		str = $.trim(str);
		
		if(str=="")
			return 0; 
			
		var length=0; 
		for(var i=0;i <str.length;i++) 
		{ 
			if(str.charCodeAt(i)>255)
				length+=2; 
			else
				length++; 
		}
		
		return length;
	}
	
	$.getLengthString=function(str,length,isSpace)
	{
		if(arguments.length < 3)
			var isSpace = true; 
		
		if($.trim(str)=="")
			return "";
		
		var tempStr="";
		var strLength = 0;
		
		for(var i=0;i <str.length;i++) 
		{
			if(str.charCodeAt(i)>255)
				strLength+=2;
			else
			{
				if(str.charAt(i) == " ")
				{
					if(	isSpace)
						strLength++;	
				}
				else
					strLength++;
			}
				
			if(length >= strLength)
				tempStr += str.charAt(i);
		}
		
		return tempStr;
	}
	
	$.getBodyScrollTop=function(){
        var scrollPos; 
        if (typeof window.pageYOffset != 'undefined') { 
            scrollPos = window.pageYOffset; 
        } 
        else if (typeof document.compatMode != 'undefined' && 
            document.compatMode != 'BackCompat') { 
            scrollPos = document.documentElement.scrollTop; 
        } 
        else if (typeof document.body != 'undefined') { 
            scrollPos = document.body.scrollTop; 
        } 
        return scrollPos;
    }
	
	$.copyText = function(id)
	{
		var txt = $(id).val();
		if(window.clipboardData)
		{
			window.clipboardData.clearData();
			var judge = window.clipboardData.setData("Text", txt);
			if(judge === true)
				alert(LANG.JS_COPY_SUCCESS);
			else
				alert(LANG.JS_COPY_NOT_SUCCESS);
		}
		else if(navigator.userAgent.indexOf("Opera") != -1)
		{
			window.location = txt;
		} 
		else if (window.netscape) 
		{
			try
			{
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			}
			catch(e)
			{
				alert(LANG.JS_NO_ALLOW);
			}
			var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
			if (!clip)
				return;
			var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
			if (!trans)
				return;
			trans.addDataFlavor('text/unicode');
			var str = new Object();
			var len = new Object();
			var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
			var copytext = txt;
			str.data = copytext;
			trans.setTransferData("text/unicode",str,copytext.length*2);
			var clipid = Components.interfaces.nsIClipboard;
			if (!clip)
				return false;
			clip.setData(trans,null,clipid.kGlobalClipboard);
			alert(LANG.JS_COPY_SUCCESS);
		}
	};
	
	$(window).scroll(function(){
		if($("#sysmsg-error") != "none" || $("#sysmsg-success") != "none")
		{
			var top = $.getBodyScrollTop();
			if(top < 150)
				top = 150;
			$("#sysmsg-error-box").stop();
			$("#sysmsg-error-box").animate({"top":top},{duration:300}); 
		}
	});
	
	$.showErr = function(str)
	{
		var top = $.getBodyScrollTop();
		if(top < 150)
			top = 150;
		$("#sysmsg-error-box").css({"top":top});
		$("#sysmsg-error span:first").html(str);
		$("#sysmsg-error").show();
		$("#sysmsg-success").hide();
		$("#sysmsg-error-box").show();
		
		clearTimeout(errHideTimeOut);
		
		var hideErr = function(){
			$("#sysmsg-error-box").slideUp(300);
		};
		
		errHideTimeOut = setTimeout(hideErr,5000);
		
		$("#sysmsg-error-box .close").one("click", function(){
			$("#sysmsg-error-box").hide();
		});
	}
	
	$.showSuccess = function(str)
	{
		var top = $.getBodyScrollTop();
		if(top < 150)
			top = 150;
		$("#sysmsg-error-box").css({"top":top});
		$("#sysmsg-success span:first").html(str);
		$("#sysmsg-success").show();
		$("#sysmsg-error").hide();
		$("#sysmsg-error-box").show();
		
		clearTimeout(errHideTimeOut);
		
		var hideErr = function(){
			$("#sysmsg-error-box").slideUp(300);
		};
		
		errHideTimeOut = setTimeout(hideErr,5000);
		
		$("#sysmsg-error-box .close").one("click", function(){
			$("#sysmsg-error-box").hide();
		});
	}
	
	$.ShowDialog=function(option,toppix)
	{
		if(toppix==null) toppix = 120;
		option = $.extend({
			dialog:null,
			html:null,
			closeFun:null
		}, option || {});
		
		var bgDiv=document.createElement("DIV");
        var selfObj=$("."+option.dialog);
        if(selfObj.length==0)
        {
            $("body").append(option.html);
            selfObj=$("."+option.dialog);
        }
		
        $("body").append(bgDiv);
        $(bgDiv).css({position:"absolute",width:$(document).width(), height:$(document).height(),top:"0",left:"0",opacity:0.3,background:"#000",display:"none","z-index":100});
        $.windowCenter(selfObj,toppix);
		selfObj.bgiframe();
		$(bgDiv).bgiframe();
		
        selfObj.show();
        $(bgDiv).show();

		$(".close",selfObj).click(function(){
            $(bgDiv).remove();
            selfObj.css({display:"none"});
			if(option.closeFun)
               option.closeFun.call(this);
        });
		
		$(window).scroll(function(){
			if(selfObj.css("display") != "none")
			{
				$.windowCenter(selfObj,toppix);
				$(bgDiv).css({width:$(document).width(), height:$(document).height()});
			}
		});
	}
	
	$.windowCenter=function(obj,toppix)
	{
		if(toppix==null) toppix = 120;
		var windowWidth=$.support.opacity ? window.innerWidth : document.documentElement.clientWidth;
		var windowHeight=$.support.opacity ? window.innerHeight : document.documentElement.clientHeight;
		var objWidth=obj.width();
		var objHeight=obj.height();
		var objTop=toppix + $.getBodyScrollTop();
		var objLeft=(windowWidth - objWidth ) / 2;
		obj.css({position:"absolute",display:"block","z-index":1000,top:objTop,left:objLeft});
	}
	
	$.minLength = function(value, length , isByte) {
		var strLength = $.trim(value).length;
		if(isByte)
			strLength = $.getStringLength(value);
			
		return strLength >= length;
	};
	
	$.maxLength = function(value, length , isByte) {
		var strLength = $.trim(value).length;
		if(isByte)
			strLength = $.getStringLength(value);
			
		return strLength <= length;
	};
	
	$.rangeLength = function(value, minLength,maxLength, isByte) {
		var strLength = $.trim(value).length;
		if(isByte)
			strLength = $.getStringLength(value);
			
		return length >= minLength && length <= maxLength;
	}
	
	$.checkMobilePhone = function(value){
		return /^(13\d{9}|18\d{9}|14\d{9}|15\d{9})$/i.test($.trim(value));
	}
	
	$.checkPhone = function(val){
  		var flag = 0;
		val = $.trim(val);
  		var num = ".0123456789/-()";
  		for(var i = 0; i < (val.length); i++)
		{
    		tmp = val.substring(i, i + 1);
    		if(num.indexOf(tmp) < 0)
      			flag++;
 		}
  		if(flag > 0)
			return true;
		else
			return false;
	}
	
	$.checkEmail = function(val){
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; 
		return reg.test(val);
	};
	
})(jQuery);

jQuery(function($){
	$(document).pngFix();
	
	imagesInit();
	
	$("#is-credit-all").bind("click",function(){
		if(!$(this).attr("checked"))
		{
			$("#credit-text").val("0");
		}
		countCartTotal();
	});
	if($("#sysmsg-error") != "none" || $("#sysmsg-success") != "none")
	{
		var hideErr = function(){
			$("#sysmsg-error-box").slideUp(300);
		};
		
		errHideTimeOut = setTimeout(hideErr,5000);
		
		$("#sysmsg-error-box .close").one("click", function(){
			$("#sysmsg-error-box").hide();
		});
	}
	
	$("#myaccount").hover(function(){
		$(this).addClass("hover");
		$("#myaccount-menu").show();		   
	},function(){
		var menuHide = function(){
			$("#myaccount").removeClass("hover");
			$("#myaccount-menu").hide();
		};
		userMenuTimeOut = setTimeout(menuHide,100);
	});
	
	$("#myaccount-menu").hover(function(){
		clearTimeout(userMenuTimeOut);
		$("#myaccount").addClass("hover");   
	},function(){
		$("#myaccount").removeClass("hover");
		$(this).hide();
	});
	
	$("#cardcode-link").click(function(){
		if($(".ecvinput").hasClass('act'))
		{
			$(".ecvinfo").addClass('act'); 
			$(".ecvinput").removeClass('act'); 
		}
		else
		{
			$(".ecvinput").addClass('act'); 
			if($(".ecvinfo").hasClass("ok"))
				$(".ecvinfo").removeClass('act'); 
		}
	});
	
	$("#cardcode-sn,#cardcode-pwd").keyup(function(){
		clearTimeout(ecvTimeOut);
		ecvTimeOut = setTimeout("countCartTotal()",200);			 
	});
	
	$("#cardcode-verify").click(function(){
		var sn = $.trim($("#cardcode-sn").val());
		var password = $.trim($("#cardcode-pwd").val());
		
		if(sn.length == 0)
		{
			$.showErr(LANG.JS_BONUS_SN_EMPTY);
			$("#cardcode-sn").focus();
			return false;
		}
		
		$.ajax({
			  url: ROOT_PATH+"/index.php?m=Ajax&a=ecvVerify&sn="+sn+"&pwd="+password,
			  cache: false,
			  type: "POST",
			  dataType: "json",
			  success:function(data)
			  {
			　 		if(data.type == 0)
					{
						$.showErr(data.msg);
						$(".ecvinfo").removeClass('ok');
						$("#cardcode-pwd").val('');
						$("#cardcode-sn").val('');						
					}
					else
					{
						$(".ecvinfo p span").eq(0).html(data.ecv.ecvType.name);
						$(".ecvinfo p span").eq(1).html(data.ecv.money);
						$(".ecvinfo p span").eq(2).html(data.ecv.use_start_date);
						$(".ecvinfo p span").eq(3).html(data.ecv.use_end_date);
						$(".ecvinput").addClass('act'); 
						$(".ecvinfo").addClass('ok').removeClass('act'); 
					}
					
					countCartTotal();
			  }
		});
		return false;	 
	});
	
	$("#guides-city-change").click(function(){
		if($("#guides-city-list").css("display") == "none")
		{
			$("#guides-city-list").show();
			$("body").one("click", function(){
				$("#guides-city-list").hide();
			}); 
		}
		else
			$("#guides-city-list").hide();
		return false;
	});
	
	$("#guides-city-list ul.P_CITYS li a").click(function(){
		var query = new Object();
		query.m = 'Ajax';
		query.a = 'getSubCitys';
		query.id = $(this).attr('rel');
		$("#guides-city-list ul.P_CITYS li").each(function(){
			$(this).removeClass('current');
		})
		
		var purl = $(this).attr('href');
		$(this).parent().addClass('current');
		$.ajax({
			url:ROOT_PATH+"/index.php",
			data:query,
			dataType:"TEXT",
			success:function(data)
			{
				if(parseInt(data)!=0)
				{
					$("#guides-city-list ul.SUB_CITYS").html(data);
					$("#guides-city-list ul.SUB_CITYS").show();
					return false;
				}
				else
				{
					window.location.href=purl;
					return true;
				}
			}
		});
		return false;
	});
	
	
	$("#deal-share-im").click(function(){
		if($("#deal-share-im-c").css("display") == "none") 
			$("#deal-share-im-c").show();
		else
			$("#deal-share-im-c").hide();
		
	});
	
	$('#share-copy-button').click(function(){
		$.copyText('#share-copy-text');
	});
	$("#enter-address-form").submit(function(){
		var email = $.trim($(this).find("#enter-address-mail").val());
		if(email.length == 0)
		{
			$.showErr(LANG.JS_EMAIL_ADDRESS_EMPTY);
			$("#enter-address-mail").focus();
			return false;
		}
		
		if(!$.checkEmail(email))
		{
			$.showErr(LANG.JS_EMAIL_ADDRESS_ERROR_EMPTY);
			$("#enter-address-mail").focus();
			return false;
		}
	
	});
	$("#ecv_incharge").submit(function(){
		var sn = $.trim($("#sn").val());		
		if(sn.length == 0)
		{
			$.showErr(LANG.JS_BONUS_SN_EMPTY);
			$("#sn").focus();
			return false;
		}

	
	});
	
	$("#ecv-form").submit(function(){
		var ecvSn = $.trim($(this).find("#ecvSn").val());
		var ecvPassword = $.trim($(this).find("#ecvPassword").val());
		if(ecvSn.length == 0)
		{
			$.showErr(LANG.JS_ECVSN_EMPTY);
			$("#ecvSn").focus();
			return false;
		}
	});
	
	$("#seller_msg").submit(function(){
		if($.trim($(this).find("#user_name").val())=='')
		{
			$.showErr(LANG.JS_USERNAME_EMPTY);
			$("#user_name").focus();
			return false;
		}
		if($.trim($(this).find("#title").val())=='')
		{
			$.showErr(LANG.JS_CONTACT_EMPTY);
			$("#title").focus();
			return false;
		}
		if($.trim($(this).find("#content").val())=='')
		{
			$.showErr(LANG.JS_GB_DESC_EMPTY);
			$(this).find("#content").focus();
			return false;
		}
		if($.trim($(this).find("#groupon_seller_name").val())=='')
		{
			$.showErr(LANG.JS_GB_USER_EMPTY);
			$("#groupon_seller_name").focus();
			return false;
		}
		if($.trim($(this).find("#groupon_goods").val())=='')
		{
			$.showErr(LANG.JS_GB_GOOD_EMPTY);
			$("#groupon_goods").focus();
			return false;
		}
	});
	
	$("#comment-form").submit(function(){
		if($.trim($(this).find("#msgcontent").val())== '')
		{
			$.showErr(LANG.JS_CONTENT_EMPTY);
			$(this).find("#msgcontent").focus();
			return false;
		}
	});
	
	$("#comments-form").submit(function(){
		if(KE.util.getData("msgcontent").length==0)
		{
			$.showErr(LANG.JS_CONTENT_EMPTY);
			return false;
		}
	});
	
	$("#add-tg-form").submit(function(){
		if($.trim($(this).find("#tg_title").val())== '')
		{
			$.showErr(LANG.JS_TITLE_EMPTY);
			$(this).find("#tg_title").focus();
			return false;
		}		
		if($.trim(KE.util.getData("tg_content"))== '')
		{
			$.showErr(LANG.JS_CONTENT_EMPTY);
			return false;
		}
	});
	$("#tg-comment").submit(function(){
		if($.trim($(this).find("#tg_content").val())== '')
		{
			$.showErr(LANG.JS_COMMENT_CONTENT_EMPTY);
			$(this).find("#tg_content").focus();
			return false;
		}
	});
	
	$("#incharge-form").submit(function(){
		
		var money = $(this).find("#money").val();
		if(money==''||isNaN(money)||parseFloat(money)<=0)
		{
			$.showErr(LANG.JS_MONEY_EMPTY);
			$("#money").focus();
			return false;
		}
		if($(this).find("input:checked").length==0)
		{
			$.showErr(LANG.JS_SELECT_PAYMENT);
			return false;
		}
	});

	$("#uncharge-form").submit(function(){
		
		var money = $(this).find("#money").val();
		if(money==''||isNaN(money)||parseFloat(money)<=0)
		{
			$.showErr(LANG.JS_MONEY_EMPTY);
			$("#money").focus();
			return false;
		}
		if($(this).find("#memo").val()=='')
		{
			$.showErr(LANG.JS_KQ_ACCOUNT_EMPTY);
			return false;
		}
	});

	$("#tip-deal-subscribe-body form,#deal-subscribe-body form").submit(function(){
		var thisform = $(this).parent();
		var email = $.trim($(".f-text",this).val());
		
		if(email.length == 0)
		{
			$.showErr(LANG.JS_EMAIL_ADDRESS_EMPTY);
			$(".f-text",this).focus();
			return false;
		}
		
		if(!$.checkEmail(email))
		{
			$.showErr(LANG.JS_EMAIL_ADDRESS_ERROR_EMPTY);
			$(".f-text",this).focus();
			return false;
		}
		//alert(ROOT_PATH+"/index.php?"+VAR_MODULE+"=Index&"+VAR_ACTION+"=subScribe&email="+email+"&cityid="+cityID);
		$.ajax({
			  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Index&"+VAR_ACTION+"=malllist&do=subScribe&uemail="+email+"&cityid="+cityID,
			  cache: false,
			  success:function(data)
			  {
				$(".f-text",thisform).val("");
				$.showSuccess(data);
			  }
		});
		return false;													  
	});
	
	$(".unsubScribeBtn").click(function(){
		var thisform = $(this).parent().parent().parent().parent().parent();
		var email = $.trim($(".f-text",thisform).val());
		
		if(email.length == 0)
		{
			$.showErr(LANG.JS_EMAIL_ADDRESS_EMPTY);
			$(".f-text",thisform).focus();
			return false;
		}
		
		if(!$.checkEmail(email))
		{
			$.showErr(LANG.JS_EMAIL_ADDRESS_ERROR_EMPTY);
			$(".f-text",thisform).focus();
			return false;
		}
		
		$.ajax({
			  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Index&"+VAR_ACTION+"=malllist&do=unSubScribe&email="+email+"&isAjax=1",
			  cache: false,
			  success:function(data)
			  {
				  $(".f-text",thisform).val("");
				  $.showSuccess(data);
			  }
		});
		return false;													  
	});
	
	$("#tip-deal-subscribe-body .f-text,#deal-subscribe-body .f-text").val("请输入你的Email...");
	
	$("#tip-deal-subscribe-body .f-text,#deal-subscribe-body .f-text").focus(function(){
		if($.trim(this.value) == LANG.JS_EMAIL_EMPTY)
			this.value = "";
			
	}).blur(function(){
		if($.trim(this.value) == "")
			this.value =  LANG.JS_EMAIL_EMPTY;												  
	});
	
	$("#attrs-row").click(function(){
		var amount=parseInt($('#deal-buy-quantity-input').val());
		attrPrice = 0;
		$("#attrs-row select option:selected").each(function(i){
			attrPrice += parseFloat(this.getAttribute('price'));						 
		});
		var goodsPrices = Math.round(amount * goodsPrice * 100) /100;
		var attrPrices = Math.round(amount * attrPrice * 100) /100;
		var totalPrice = goodsPrices + attrPrices;
		
		if(goodsPrices != 0)
			goodsPrices = LANG.JS_PP+ goodsPrices;
		else
			goodsPrices = LANG.JS_FREE;
			
		
		if(totalPrice != 0)
			totalPrice = LANG.JS_PP+ totalPrice;
		else
			totalPrice = LANG.JS_FREE;
			
		$('#deal-buy-total').html(goodsPrices);
		$('#deal-buy-total-t').html(totalPrice);
		$('#deal-attr-price').html(LANG.JS_PP+ attrPrice);
		$('#deal-attr-total-t').html(LANG.JS_PP+ attrPrices);
	})
	
	$("#deal-buy-quantity-input").keydown(function(event){
		var event=event?event:window.event;
		var k=event.keyCode;
		if(!(k==8 || k==9 || k==13 || k==16 || k>=33 && k<=40 || k==45 || k==46 || k>=48 && k<=57 || k>=96 && k<=105))
		{
			return false;
		}
	})/*.keyup(function(){
		var amount=parseInt($('#deal-buy-quantity-input').val());
		
		if(!isNaN(amount))
		{
			if(amount < 1)
			{
				$.showErr(LANG.JS_BUYNUM_LT_1);
				$('#deal-buy-quantity-input').val(1);
				amount = 1;
			}
			else
			{
				$bln = false;
				$err = "";
				
				if(amount + userBuyCount > maxBought && maxBought > 0)
				{
					amount = maxBought - userBuyCount;
					$bln = true;
				}
				
				if(amount > surplusCount && goodsStock > 0)
				{
					amount = surplusCount;
					$bln = true;
				}
				
				
				
				if($bln)
				{
					if(maxBought > 0)
						$err+=LANG.JS_EVERY_BODY+maxBought+LANG.JS_HOW_GOODS;
						
					if(goodsStock > 0)
						$err+=LANG.JS_LIMIT_1+surplusCount+LANG.JS_LIMIT_2+(($err == "") ? LANG.JS_GOODS_T : "")+LANG.JS_LIMIT_6;
						
					$.showErr($err + LANG.JS_LIMIT_3 + userBuyCount+LANG.JS_LIMIT_4+amount+LANG.JS_LIMIT_2+LANG.JS_LIMIT_5);
					
				}
				
				$('#deal-buy-quantity-input').val(amount);
			}
			
			var goodsPrices = Math.round(amount * goodsPrice * 100) /100;
			var attrPrices = Math.round(amount * attrPrice * 100) /100;
			var totalPrice = goodsPrices + attrPrices;
			
			if(goodsPrices != 0)
				goodsPrices = LANG.JS_PP+ goodsPrices;
			else
				goodsPrices = LANG.JS_FREE;
				
			
			if(totalPrice != 0)
				totalPrice = LANG.JS_PP+ totalPrice;
			else
				totalPrice = LANG.JS_FREE;
				
			$('#deal-buy-total').html(goodsPrices);
			$('#deal-buy-total-t').html(totalPrice);
			$('#deal-attr-total-t').html(LANG.JS_PP+ attrPrices);
		}
	}).blur(function(){
		var amount=parseInt($('#deal-buy-quantity-input').val());
		if(isNaN(amount))
			amount = 1;
			
		if(amount < 1)
		{
			$.showErr(LANG.JS_BUYNUM_LT_1);
			$('#deal-buy-quantity-input').val(1);
			amount = 1;
		}
		else
		{
			$bln = false;
			$err = "";
			
			if(amount + userBuyCount > maxBought && maxBought > 0)
			{
				amount = maxBought - userBuyCount;
				$bln = true;
			}
			
			if(amount > surplusCount && goodsStock > 0)
			{
				amount = surplusCount;
				$bln = true;
			}
			
			
			
			if($bln)
			{
				if(maxBought > 0)
					$err+=LANG.JS_EVERY_BODY+maxBought+LANG.JS_HOW_GOODS;
					
				if(goodsStock > 0)
					$err+=LANG.JS_LIMIT_1+surplusCount+LANG.JS_LIMIT_2+(($err == "") ? LANG.JS_GOODS_T : "")+LANG.JS_LIMIT_6;
					
				$.showErr($err + LANG.JS_LIMIT_3+userBuyCount+LANG.JS_LIMIT_4+amount+LANG.JS_LIMIT_2+LANG.JS_LIMIT_5);
				
			}
			
			$('#deal-buy-quantity-input').val(amount);
		}
		
		var goodsPrices = Math.round(amount * goodsPrice * 100) /100;
		var attrPrices = Math.round(amount * attrPrice * 100) /100;
		var totalPrice = goodsPrices + attrPrices;
		
		if(goodsPrices != 0)
			goodsPrices = LANG.JS_PP+ goodsPrices;
		else
			goodsPrices = LANG.JS_FREE;
			
		
		if(totalPrice != 0)
			totalPrice = LANG.JS_PP+ totalPrice;
		else
			totalPrice = LANG.JS_FREE;
			
		$('#deal-buy-total').html(goodsPrices);
		$('#deal-buy-total-t').html(totalPrice);
		$('#deal-attr-total-t').html(LANG.JS_PP+ attrPrices);
	})*/;
	
	$("#credit-text").keydown(function(event){
		var event=event?event:window.event;
		var k=event.keyCode;
		if(!(k==8 || k==9 || k==13 || k==16 || k>=33 && k<=40 || k==45 || k==46 || k>=48 && k<=57 || k>=96 && k<=105 || k==190))
		{
			return false;
		}
	}).blur(function(){
		var money = getRoundFloat(this.value);
		maxMoney = getRoundFloat(maxMoney.toString());
		totalPrice = getRoundFloat(totalPrice.toString());
		
		if(money > maxMoney)
		{
			$.showErr(LANG.JS_MONEY_NO_LT+LANG.JS_PP+maxMoney+LANG.JS_LIMIT_5);
			$(this).val(maxMoney);
		}
		else
		{
			$(this).val(money);
		}
		countCartTotal();
	});
	
	$("#order_done").click(function(){
		var ret=true;
		if(is_smzq == 0 && goodsType == 1 && totalPrice >= 0)
		{
			if($.trim($("#delivery-consignee").val()).length == 0&&$("input[name='delivery_refer_order_id']:checked").length == 0)
			{
				$.showErr(LANG.JS_CONSIGNEE_NAME_ENPTY);
				return false;	
			}
			
			if($("#region_lv1_0").val() == 0&&$("input[name='delivery_refer_order_id']:checked").length==0)
			{
				$.showErr(LANG.JS_SELECT_COUNTRT);
				return false;	
			}
			else
			{
				if($("#region_lv2_0 option").length > 0&&$("input[name='delivery_refer_order_id']:checked").length==0)
				{
					if($("#region_lv2_0").val() == 0&&$("input[name='delivery_refer_order_id']:checked").length == 0)
					{
						$.showErr(LANG.JS_PROVINCE);
						return false;	
					}
					else
					{
						if($("#region_lv3_0 option").length > 0&&$("input[name='delivery_refer_order_id']:checked").length==0)
						{
							if($("#region_lv3_0").val() == 0&&$("input[name='delivery_refer_order_id']:checked").length == 0)
							{
								$.showErr(LANG.JS_CITY);
								return false;	
							}
							else
							{
								if($("#region_lv4_0 option").length > 0&&$("input[name='delivery_refer_order_id']:checked").length==0)
								{
									if($("#region_lv4_0").val() == 0&&$("input[name='delivery_refer_order_id']:checked").length == 0)
									{
										$.showErr(LANG.JS_AREA);
										return false;	
									}
								}
							}
						}
					}
				}
			}
			
			if($.trim($("#delivery-address").val()).length < 5&&$("input[name='delivery_refer_order_id']:checked").length == 0)
			{
				$.showErr(LANG.JS_ADDRESS_NOT_NULL);
				return false;	
			}
			
			if($.trim($("#delivery-zip").val()).length == 0&&$("input[name='delivery_refer_order_id']:checked").length == 0)
			{
				$.showErr(LANG.JS_POST);
				return false;	
			}
			
			if($.trim($("#delivery-fix-phone").val()).length ==　0 && $.trim($("#delivery-mobile-phone").val()).length ==　0 &&$("input[name='delivery_refer_order_id']:checked").length==0)
			{
				$.showErr(LANG.JS_PHONE_OR_MOBILE);
				return false;	
			}
			else
			{
				if($.checkPhone($("#delivery-fix-phone").val())&&$("#delivery-fix-phone").val().length > 0)
				{
					$.showErr(LANG.JS_PHONT_ERRER);
					return false;	
				}
				
				if(!$.checkMobilePhone($("#delivery-mobile-phone").val())&&$("input[name='delivery_refer_order_id']:checked").length == 0)
				{
					$.showErr(LANG.JS_MOBILE_ERROR);
					return false;	
				}	
			}
			
			if(isInquiry == 0)
			{				
				if($("input[name='delivery']:checked").length == 0&&$("input[name='delivery_refer_order_id']:checked").length == 0)
				{
					$.showErr(LANG.JS_SELECT_SHIPPING_METHOD);
					return false;
				}
			}
		}
		
		if($.trim($("#user-mobile-phone").val()).length > 0 && $.checkPhone($("#user-mobile-phone").val())&&$("input[name='delivery_refer_order_id']:checked").length == 0)
		{
			$.showErr(LANG.JS_BOTH_MOBILE);
			return false;	
		}
		
		if(totalPrice > 0)
		{
			if($("input[name='payment']:checked").length == 0)
			{
				$.showErr(LANG.JS_SELECT_PAYMENT);
				return false;
			}
		}
		//add by chenfq 2010-09-29
		cart_done();
	});
});

//add by chenfq 2010-09-29
var cart_done_ing = false; 
function cart_done(){
	$("#order_done").attr("disabled",true);
	if (cart_done_ing){//add by chenfq 2011-03-17 数据正在处理中，请务重复提交.
		alert(LANG.CART_DONE_ING);
		return false;
	}
	cart_done_ing = true;
	var query = new Object();
	if (isOrder==false){
		//var pamrm = "?m=Cart&a=done";
		query.m = "Cart";
		query.a = "done";
	}else{
		//var pamrm = "?m=Order&a=done&order_id=" + orderID;
		query.m = "Order";
		query.a = "done";
		query.order_id = orderID;
	}	
	
		//开始获取提交的数据
		
	var delivery_id = 0;  //配送方式
	var payment_id =  0;   //支付方式
	var is_protect =  0;    //是否保价
	var	delivery_refer_order_id = 0; //快递拼单
			
	//提交的地区	
	var region_lv1 = $("#region_lv1_0").val();   //一级地区
	var region_lv2 = $("#region_lv2_0").val();   //二级地区
	var region_lv3 = $("#region_lv3_0").val();   //三级地区
	var region_lv4 = $("#region_lv4_0").val();   //四级地区
		
	//pamrm = pamrm + "&region_lv1=" + region_lv1 + "&region_lv2=" + region_lv2 + "&region_lv3=" + region_lv3 + "&region_lv4=" + region_lv4;
	query.region_lv1 = region_lv1;
	query.region_lv2 = region_lv2;
	query.region_lv3 = region_lv3;
	query.region_lv4 = region_lv4;
	
	if($("input[name='payment']:checked").length > 0)
		payment_id = $("input[name='payment']:checked").val();
		
	if($("input[name='delivery']:checked").length > 0)
	{
		delivery_id = $("input[name='delivery']:checked").val();
		var parent = $("input[name='delivery']:checked").parent().parent();
		if($(".protect:checked",parent).length > 0)
			is_protect = 1;
	}
	
	var credit = $("#credit-text").val();
	var iscreditall = $("#credit-all input").attr("checked") ? 1 : 0;
	
	//pamrm = pamrm + "&payment_id=" + payment_id + "&delivery_id=" + delivery_id + "&credit=" + credit + "&iscreditall=" + iscreditall;	
	query.payment_id = payment_id;
	query.delivery_id = delivery_id;
	query.credit = credit;
	query.iscreditall = iscreditall;
	
	//是否开票
	var tax = $("#tax").attr("checked")?1:0;
	var ecvSn = $.trim($("#cardcode-sn").val());
	var ecvPassword = $.trim($("#cardcode-pwd").val());


	//pamrm = pamrm + "&is_protect=" + is_protect + "&tax=" + tax + "&ecv_sn=" + ecvSn + "&ecv_password=" + ecvPassword;	
	query.is_protect = is_protect;
	query.tax = tax;
	query.ecv_sn = ecvSn;
	query.ecv_password = ecvPassword;	
		
		
	var memo = $.trim($("#memo").val());
	var tax_content = $.trim($("#tax_content").val());
		
	//pamrm = pamrm + "&memo=" + memo + "&tax_content=" + tax_content;
	query.memo = memo;
	query.tax_title = $.trim($("#tax_title").val()); //add by chenfq 2011-03-17
	query.tax_content = tax_content;	
	
	//收信地址	
	var consignee = $.trim($("#delivery-consignee").val());	
	var address = $.trim($("#delivery-address").val());
	var zip = $.trim($("#delivery-zip").val());
	var fix_phone = $.trim($("#delivery-fix-phone").val());
	var mobile_phone = $.trim($("#delivery-mobile-phone").val());
		
	//pamrm = pamrm + "&consignee=" + consignee + "&address=" + address + "&zip=" + zip + "&fix_phone=" + fix_phone + "&mobile_phone=" + mobile_phone;
	query.consignee = consignee;
	query.address = address;
	query.zip = zip;
	query.fix_phone = fix_phone;
	query.mobile_phone = mobile_phone;
	
	//快递拼单	
	if($("input[name='delivery_refer_order_id']:checked").length > 0)
		delivery_refer_order_id = $("input[name='delivery_refer_order_id']:checked").val();
		
	var user_mobile_phone = $.trim($("#user-mobile-phone").val());
					
	//pamrm = pamrm + "&delivery_refer_order_id=" + delivery_refer_order_id + "&user_mobile_phone=" + user_mobile_phone;		
	query.delivery_refer_order_id = delivery_refer_order_id;
	query.user_mobile_phone = user_mobile_phone;

	//===========add by chenfq 2011-06-29 begin=========================
	//alert($("*[payid='"+payment_id+"']").length);
	$.each($("*[payid='"+payment_id+"']"),function(i,n)
			 {
				query[n.name] = n.value;
			});	
	//===========add by chenfq 2011-06-29 end=========================	
	
	//var url = "services/cart.php" + pamrm;
	var url = "services/cart.php";
	
	$.ajax({
		url: url,
		cache: false,
		type: "POST",
		data: query,
		dataType:"json",
		success:function(data)
		{
			var rs = data;
			if (rs.status == false){
				alert(rs.error);
				$("#order_done").attr("disabled",false);
			}else{
				var url = "index.php?m=Order&a=pay&pay=1&id=" + rs.order_id+"&accountpay_str=" + rs.accountpay_str + "&ecvpay_str=" + rs.ecvpay_str;
				if (rs.money_status == 2){
					url = "index.php?m=Order&a=pay_success&id=" + rs.order_id;
				}
				location.href = url; 
			}							
		},
		error:function(a,b,c)
		{
			if(a.responseText)
				alert(a.responseText);
		}
	});
	cart_done_ing = false;
	$("#order_done").attr("disabled",false);
}

function getRoundFloat(x)
{
	if(isNaN(x))
		return 0;
	
	var float=0;
	if(isNaN(x) || $.trim(x) == "")
		return 0;
	else
		float = parseFloat(x); 
	
	if(float < 0)
		return 0;
		
	return Math.round(float * 100) / 100;
}

//地区切换
function selectRegion(obj,region_id,lvl)
{
	var id=obj.value;
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=Ajax&"+VAR_ACTION+"=getChildRegion&is_ajax=1&pid="+id,
		  success:function(data)
		  {
			data = $.evalJSON(data); 
			var origin_html = "<option value='0'>"+NO_SELECT+"</option>";
			switch(lvl)
			{				
				case 1:	
					html = origin_html;
					if(data)
					for(var i=0;i<data.length;i++)
					{
						html+="<option value='"+data[i].id+"'>"+data[i].name+"</option>";
					}
					if(id==0) html = origin_html;  //当未作选择时清空
					$("#region_lv2_"+region_id).html(html);
					$("#region_lv3_"+region_id).html(origin_html);
					$("#region_lv4_"+region_id).html(origin_html);
					break;
				case 2:
					html = origin_html;
					if(data)
					for(var i=0;i<data.length;i++)
					{
						html+="<option value='"+data[i].id+"'>"+data[i].name+"</option>";
					}
					if(id==0) html = origin_html;  //当未作选择时清空
					$("#region_lv3_"+region_id).html(html);
					$("#region_lv4_"+region_id).html(origin_html);
					break;
				case 3:
					html = origin_html;
					if(data)
					for(var i=0;i<data.length;i++)
					{
						html+="<option value='"+data[i].id+"'>"+data[i].name+"</option>";
					}
					if(id==0) html = origin_html;  //当未作选择时清空
					$("#region_lv4_"+region_id).html(html);
					break;
				}
		  }
	}); 
}

function selectRegionDelivery(obj,region_id,lvl)
{
	var id=obj.value;
	var origin_html = "<option value='0'>"+NO_SELECT+"</option>";
	html = origin_html;
	switch(lvl)
	{				
		case 1:	
			if(id > 0)
			{
				var evalStr="regionConf.r"+id+".c";
				var regionConfs=eval(evalStr);
				evalStr+=".";
				for(var key in regionConfs)
				{
					html+="<option value='"+eval(evalStr+key+".i")+"'>"+eval(evalStr+key+".n")+"</option>";
				}
			}

			$("#region_lv2_"+region_id).html(html);
			$("#region_lv3_"+region_id).html(origin_html);
			$("#region_lv4_"+region_id).html(origin_html);
			break;
		case 2:
			if(id > 0)
			{
				var evalStr="regionConf.r"+$("#region_lv1_"+region_id).val()+".c.r"+id+".c";
				var regionConfs=eval(evalStr);
				evalStr+=".";
				for(var key in regionConfs)
				{
					html+="<option value='"+eval(evalStr+key+".i")+"'>"+eval(evalStr+key+".n")+"</option>";
				}
			}
			
			$("#region_lv3_"+region_id).html(html);
			$("#region_lv4_"+region_id).html(origin_html);
			break;
		case 3:
			if(id > 0)
			{
				var evalStr="regionConf.r"+$("#region_lv1_"+region_id).val()+".c.r"+$("#region_lv2_"+region_id).val()+".c.r"+id+".c";
				var regionConfs=eval(evalStr);
				evalStr+=".";
				for(var key in regionConfs)
				{
					html+="<option value='"+eval(evalStr+key+".i")+"'>"+eval(evalStr+key+".n")+"</option>";
				}
			}
			
			$("#region_lv4_"+region_id).html(html);
			break;
	}
	
	loadDelivery();
}

//读取配送方式
function loadDelivery()
{
	var id = 0;
	if(parseInt($("#region_lv4_0").val())>0)
	{
		id = parseInt($("#region_lv4_0").val());
	}
	else if(parseInt($("#region_lv3_0").val())>0)
	{
		id = parseInt($("#region_lv3_0").val());
	}
	else if(parseInt($("#region_lv2_0").val())>0)
	{
		id = parseInt($("#region_lv2_0").val());
	}
	else if(parseInt($("#region_lv1_0").val())>0)
	{
		id = parseInt($("#region_lv1_0").val());
	}
	
	var url = "services/cart.php?m=Cart&a=loadDelivery&id="+id;
	
	$.ajax({
		  //url: APP+"?"+VAR_MODULE+"=Cart&"+VAR_ACTION+"=loadDelivery&id="+id,
		  url : url,
		  cache: false,
		  success:function(data)
		  {
		  	$("#cart_delivery").html(data);
			countCartTotal();
		  }
	});
	
}

//切换配送方式
function deliveryChange(obj)
{
	$("input[name='delivery_refer_order_id']").attr("checked",false);
	$(".consignee-box").show();
	$("input.protect").attr({"disabled":true,"checked":false});
	obj.checked = true;
   	$("input",$(obj).parent().parent()).attr("disabled",false);
	
	//开始获取货到付款是否允许
	var id = 0;  //地区ID
	if(parseInt($("#region_lv4_0").val())>0)
	{
		id = parseInt($("#region_lv4_0").val());
	}
	else if(parseInt($("#region_lv3_0").val())>0)
	{
		id = parseInt($("#region_lv3_0").val());
	}
	else if(parseInt($("#region_lv2_0").val())>0)
	{
		id = parseInt($("#region_lv2_0").val());
	}
	else if(parseInt($("#region_lv1_0").val())>0)
	{
		id = parseInt($("#region_lv1_0").val());
	}

	var url = "services/cart.php?m=Cart&a=checkCod2&region_id="+id+"&delivery_id="+obj.value;
	$.ajax({
		  url: url,
		  cache: false,
		  dataType:'json',
		  success:function(data)
		  {
			 if(data.allow_cod==1)
			 {
			 	$("#payment_Cod").show();			 	
			 }
			 else
			 {
			 	$("#payment_Cod").hide();
			 	$("#payment_Cod").find("input").attr("checked",false);
			 }
			 if(data.is_smzq==1){
				 $("#consignee_region_id").hide(); 
			 }else{
				 $("#consignee_region_id").show(); 
			 }
			 is_smzq = data.is_smzq;
			 countCartTotal();	
		  }
	});
}

//计算订单中所有费用
function countCartTotal()
{
	$("#order_done").attr("disabled",true);
	var delivery_id = 0;  //配送方式
	var payment_id =  0;   //支付方式
	var is_protect =  0;    //是否保价
	var region_lv1 = $("#region_lv1_0").val();   //一级地区
	var region_lv2 = $("#region_lv2_0").val();   //二级地区
	var region_lv3 = $("#region_lv3_0").val();   //三级地区
	var region_lv4 = $("#region_lv4_0").val();   //四级地区
	
	var tax = $("#tax").attr("checked")?1:0;
	var credit = $("#credit-text").val();
	var isCreditAll = $("#credit-all input").attr("checked") ? 1 : 0;
	
	var ecvSn = $.trim($("#cardcode-sn").val());
	var ecvPassword = $.trim($("#cardcode-pwd").val());
		
	if($("input[name='delivery']:checked").length > 0)
	{
		delivery_id = $("input[name='delivery']:checked").val();
		var parent = $("input[name='delivery']:checked").parent().parent();
		if($(".protect:checked",parent).length > 0)
			is_protect = 1;
	}
		
	if($("input[name='payment']:checked").length > 0)
		payment_id = $("input[name='payment']:checked").val();
		
	var query=new Object();
	query.m = "Cart";
	query.a = "getCartTotal";
	query.delivery_id = delivery_id;
	query.payment_id = payment_id;
	query.is_protect = is_protect;
	query.region_lv1 = region_lv1;
	query.region_lv2 = region_lv2;
	query.region_lv3 = region_lv3;
	query.region_lv4 = region_lv4;
	query.tax = tax;
	query.isCreditAll = isCreditAll;
	query.credit = credit;
	query.ecvSn = ecvSn;
	query.ecvPassword = ecvPassword;
	if(isOrder)
	{
		query.id = orderID;
		query.m = "Order";
		query.a = "getOrderTotal";
	}
	$.ajax({
		  type: "POST", 	
		  url: "services/cart.php",
		  data:query,
		  cache: false,
		  dataType:'json',
		  success:function (data)
		  {
			if(data.total_price == 0 && (data.credit > 0 || data.ecvFee > 0))
			{
				if(payType == 1)
					$("#payment-list").hide();
				else
					$("#payment-list").show();
					
				$("input[name='payment']").attr("checked",false);
			}
			else
			{
				$("#payment-list").show();
			}
			totalPrice = data.total_price;
			
			if(totalPrice > 0)
				$("#accountpay-desc").html(LANG.JS_NO_ENOUGH_1+totalPrice+LANG.JS_NO_ENOUGH_2);
			else
				$("#accountpay-desc").html(LANG.JS_USE_BALANCE_PAY);
				
			$("#credit-text").val(data.credit);
			$("#cart_total_box").html(data.html);
			$("#order_done").attr("disabled",false);
		  },
			error:function(a,b,c)
			{
				alert(a.responseText);
			}
	});
}

//是否开票
function checkTax(obj)
{
	if(obj.checked)
	{
		$("#tax-table").removeClass("hidd");
		$("#tax_content").attr("disabled",false);		
	}
	else
	{
		$("#tax-table").addClass("hidd");
		$("#tax_content").attr("disabled",true);
		$("#tax_content").val("");
	}
	countCartTotal();
}

function toggleTabs(id)
{
	var menus = $(id).find("li").find("a");
	var tabs = $(id).find(".goods_list");
	for(var i=0;i<menus.length;i++)
	{
		if(i>0)
		{
			tabs[i].style.display = "none";
		}
		else
		{
			tabs[i].style.display = "block";
			menus[i].className = "act";
		}
		
		menus[i].onmouseover = function(){ 
			for(var i=0;i<menus.length;i++)
			{
				if(menus[i]==this)
				{
					$(tabs[i]).fadeIn();
					menus[i].className = "act";
				}
				else
				{
					$(tabs[i]).hide();
					menus[i].className = "";
				}
			}
		}
	}
}

function init_gallery()
{
	var big_imgs = $("#big_img").find("div");
	var small_imgs = $("#small_img").find("li a");
	for(var i=0;i<small_imgs.length;i++)
	{
		small_imgs[i].onmouseover = function()
		{
			for(var j=0;j<small_imgs.length;j++)
			{
				if(small_imgs[j]==this)
				{
					small_imgs[j].className = "act";
					if(big_imgs[j].style.display != "block")
						big_imgs[j].style.display = "block"
				}
				else
				{
					small_imgs[j].className = "";
					big_imgs[j].style.display="none";
				}
			}
		}
	}
}


/* 会员区用到的JS */
function sw_detail(obj)
{
	if(obj.checked)
		$("#detail_table").slideDown();
	else
		$("#detail_table").slideUp();
}
function show_check_rs(obj, rs, tipid)
{
	if(!rs.state)
		{
			if(document.getElementById(tipid))
			{
				$("#"+tipid).html(rs.msg);	 				
			}
			else
			{
				$(obj.parentNode).append("<span id='"+tipid+"'>"+rs.msg+"</span>");
			}			
			return false;
		}
		else
		{
			if(document.getElementById(tipid))
			{
				$("#"+tipid).html("");
			}
			return true;
		}	
}
function check_field(obj,is_ajax)
{
	switch(obj.name)
	{
		case "user_name":
			rs = new Object();
			rs.state=true;
			if(obj.value.length < 6)
			{
				rs.state = false;
				rs.msg = USER_TOO_SHORT;
			}
			if(!show_check_rs(obj,rs,'name_tip'))return;
			if(is_ajax)
			$.ajax({
				  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=User&"+VAR_ACTION+"=checkField&fieldName=user_name&value="+obj.value,
				  cache: false,
				  success:function (html){
				 		var rs = $.evalJSON(html);
				 		show_check_rs(obj,rs,'name_tip');
					}
				});
			break;
		case "user_pwd":
			rs = new Object();
			rs.state=true;
			if(obj.value.length < 6)
			{
				rs.state = false;
				rs.msg = PWD_TOO_SHORT;
			}
			
			show_check_rs(obj,rs,'pwd_tip');
			break;
		case "user_pwd_confirm":
			rs = new Object();
			rs.state=true;
			if(document.getElementById("passwordbox").value!=document.getElementById("confirm_passwordbox").value)
			{				
				rs.state = false;
				rs.msg = PWD_CONFIRM_FAILED;
			}			
			show_check_rs(obj,rs,'cfpwd_tip');
			break;
		case "email":			
			//非ajax验证
			email_reg = /^\w+[\@]{1}\w+[\.]{1}\w{2,3}$/;
			rs = new Object();
			rs.state=true;
			if(!email_reg.test(obj.value))
			{				
				rs.state = false;
				rs.msg = EMAIL_FORMAT_ERROR;
			}			
			if(!show_check_rs(obj,rs,'email_tip'))return;
			if(is_ajax)
			$.ajax({
				  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=User&"+VAR_ACTION+"=checkField&fieldName=email&value="+obj.value,
				  cache: false,
				  success:function (html){
				 		var rs = $.evalJSON(html);
				 		show_check_rs(obj,rs,'email_tip');
					}
				});
			break;
		default:
			break;
	}
}
/* 会员区JS结束 */

function showAttrSearch(obj)
{
	$.ajax({
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=getTypeAttrList&type_id="+obj.value,
		  cache: false,
		  success:function (html){
		 		var rs = $.evalJSON(html);
		 		var res_html = "";
		 		if(rs)
		 		for(var i=0;i<rs.length;i++)
		 		{
		 			res_html+=rs[i].name+"：<input type='text' name='attr_value_"+rs[i].id+"' />";
		 			res_html+="<div class='blank5'></div>";
		 		}
		 		$("#attr_item").html(res_html);
			}
		});
}

function checkKeywords()
{
	var k = $("#head_keywords").val();
	if(k=='')
	{
		alert(KEYWORDS_EMPTY);
		document.getElementById("head_keywords").focus();
		return false;
	}
	else
	{
		return true;
	}
}

function reply_message(obj)
{
	var reply_id = $(obj.parentNode).find("input").val();
	var reply_title = $(obj.parentNode).find("span").html();

	if(reply_id&&reply_id!=0)
	{
		document.getElementById("message_title").value = "["+REPLY+"] "+reply_title;
		document.getElementById("message_title_span").innerHTML = "["+REPLY+"] "+reply_title;
		document.getElementById("message_title").style.display = "none";
		document.getElementById("pid").value = reply_id;
	}
}
function reset_reply()
{
	document.getElementById("message_title").value = "";
	document.getElementById("message_content").value = "";
	document.getElementById("message_title_span").innerHTML = "";
	document.getElementById("message_title").style.display = "";
	document.getElementById("pid").value = "0";	
}

/*加入购物车*/
// 购买规格（商品）
function addGoodsToCart(goods_id)
{
	spec_item_id = $("#spec_item").val();

		var number = 1;
		if(document.getElementById("number"))
		{
			var num = document.getElementById("number").value;
			if(isNaN(num)) {alert(IS_NAN);return;}			
			number = parseInt(num)<0?0:num;
		}
		$.ajax({
			  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Cart&"+VAR_ACTION+"=addGoodsToCart&is_ajax=1&rec_module=GoodsSpecItem&rec_id="+spec_item_id+"&number="+number+"&goods_id="+goods_id,
			  cache: false,
			  success:function (html){
					//alert(html);return;
			 		var rs = $.evalJSON(html);
			 		if(rs.status==0)
			 		{
			 			alert(rs.info);
			 		}
			 		else
			 		{
			 			location.href= ROOT_PATH+"/index.php?"+VAR_MODULE+"=Cart&"+VAR_ACTION+"=listCart";
			 		}			 		
				}
			});	
}

function modify_cart(id)
{
	number = document.getElementById("number_"+id).value;
	if(isNaN(number))
	{
		alert(IS_NAN);
		return;
	}
	
	$.ajax({
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Cart&"+VAR_ACTION+"=modifyCart&is_ajax=1&cart_id="+id+"&number="+number,
		  cache: false,
		  success:function (html){
		 		var rs = $.evalJSON(html);
		 		if(rs.status==0)
		 		{
		 			alert(rs.info);
		 		}
		 		else
		 		{
		 			location.href= location.href;
		 		}			 		
			}
		});
	
}

function del_cart(id)
{
	
	$.ajax({
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Cart&"+VAR_ACTION+"=delCart&is_ajax=1&cart_id="+id,
		  cache: false,
		  success:function (html){
		 		var rs = $.evalJSON(html);
		 		if(rs.status==0)
		 		{
		 			alert(rs.info);
		 		}
		 		else
		 		{

		 			if(rs.count!=0)
		 			{
		 				location.href= ROOT_PATH+"/index.php?"+VAR_MODULE+"=Cart&"+VAR_ACTION+"=listCart";
		 			}
		 			else
		 			{		 				
		 				location.href= ROOT_PATH;
		 			}
		 		}			 		
			}
		});
}


/*加入购物车*/
function addScoreCart(id)
{
	spec_item_id = $("#spec_item").val();
	
	if(id)
	{	
		var attr_str = "";
		if(document.getElementById("attr_table"))
		{
			var attrs = $("#attr_table").find("input");
			var attrs_select = $("#attr_table").find("option");
			if(attrs.length>0)
			{
				for(var i=0;i<attrs.length;i++)
				{
					if(attrs[i].checked)
					{
						attr_str += attrs[i].value+",";
					}
				}
				attr_str = attr_str.substr(0,attr_str.length-1);
			}
			else if(attrs_select.length>0)
			{
				for(var i=0;i<attrs_select.length;i++)
				{
					if(attrs_select[i].selected)
					{
						attr_str += attrs_select[i].value+",";
					}
				}
				attr_str = attr_str.substr(0,attr_str.length-1);				
			}
		}
		var number = 1;
		if(document.getElementById("number"))
		{
			var num = document.getElementById("number").value;
			if(isNaN(num)) {alert(IS_NAN);return;}			
			number = parseInt(num)<0?0:num;
		}
		$.ajax({
			  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Cart&"+VAR_ACTION+"=addGoodsToCart&is_ajax=1&rec_module=ScoreGoods&rec_id="+spec_item_id+"&attr_str="+attr_str+"&number="+number+"&goods_id="+id,
			  cache: false,
			  success:function (html){

			 		var rs = $.evalJSON(html);
			 		if(rs.status==0)
			 		{
			 			alert(rs.info);
			 		}
			 		else
			 		{
			 			location.href= ROOT_PATH+"/index.php?"+VAR_MODULE+"=Cart&"+VAR_ACTION+"=listCart";
			 		}			 		
				}
			});
	}
}

function del_consignee(id)
{
	if(confirm(CONFIRM_DELETE))
	location.href = APP+"?"+VAR_MODULE+"=UcConsignee&"+VAR_ACTION+"=delete&id="+id;
}

function cal_price()
{
	
	var spec_type_specs = $(".spec_type_input");
	
	var str = '';
	
	for(var i=0;i<spec_type_specs.length;i++)
	{
		var btns = $(spec_type_specs[i].parentNode).find("a");
		for(var j=0;j<btns.length;j++)
		{
			
			if(btns[j].id == "spec_item_"+spec_type_specs[i].value)
			{
				$(btns[j]).addClass("act");
			}
			else
			{
				$(btns[j]).removeClass("act");
			}

		}
		
		type_id_arr = (spec_type_specs[i].id).split("_");
		type_id = type_id_arr[1];
		str = str + type_id+"_" + spec_type_specs[i].value + ",";
	}
	str = str.substr(0,str.length-1);
	var goods_id = $("#goods_id").val();
	var num = $("#number").val();
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=getSpecItem&goods_id="+goods_id+"&str="+str+"&num="+num,
		  cache: false,
		  success: function(html){
								 		var rs = $.evalJSON(html);
										if(rs.sn!='')
								 		$("#sn").html(rs.sn);
								 		$("#weight").html(rs.weight_format);								 		
								 		$("#shop_price").html(rs.shop_price_format);
								 		$("#member_price").html(rs.member_price_format);
								 		$("#total_price").html(rs.total_price_format);
								 		$("#stock").html(rs.stock);	 	
										$("#spec_item").val(rs.spec_item_id);
								 		$("#spec_choose").html(rs.choose_msg);
										
										if(rs.status)
										$("#buy_button").attr("disabled",false);
										else
										$("#buy_button").attr("disabled",true);
								
		  
		  }
		});
	
	
}

function cal_score()
{
	
	var spec_type_specs = $(".spec_type_input");
	
	var str = '';
	
	for(var i=0;i<spec_type_specs.length;i++)
	{
		var btns = $(spec_type_specs[i].parentNode).find("a");
		for(var j=0;j<btns.length;j++)
		{
			
			if(btns[j].id == "spec_item_"+spec_type_specs[i].value)
			{
				$(btns[j]).addClass("act");
			}
			else
			{
				$(btns[j]).removeClass("act");
			}

		}
		
		type_id_arr = (spec_type_specs[i].id).split("_");
		type_id = type_id_arr[1];
		str = str + type_id+"_" + spec_type_specs[i].value + ",";
	}
	str = str.substr(0,str.length-1);
	var goods_id = $("#goods_id").val();
	var num = $("#number").val();
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=ScoreGoods&"+VAR_ACTION+"=getSpecItem&goods_id="+goods_id+"&str="+str+"&num="+num,
		  cache: false,
		  success:function (html){
				var rs = $.evalJSON(html);
				if(rs.sn!='')
				$("#sn").html(rs.sn);
				$("#weight").html(rs.weight_format);
				$("#stock").html(rs.stock);	
				$("#total_score").html(rs.total_score_format);		
				$("#spec_item").val(rs.spec_item_id);
				$("#spec_choose").html(rs.choose_msg);	
				if(rs.status)
				$("#buy_button").attr("disabled",false);
				else
				$("#buy_button").attr("disabled",true);	
			}
		});
	
	
}

// 开始规格的相关脚本
function setSpec(spec_id,obj)
{
	if (obj.className == 'act') {
		$(obj.parentNode).find(".spec_type_input").val(0);
	}
	else {
		$(obj.parentNode).find(".spec_type_input").val(spec_id);
	}
	getGalleryBySpec();
	cal_price();
}

// 开始规格的相关脚本
function getGalleryBySpec()
{
	var gallerys = new Object();
	var galleryIDs = new Array();
	$(".spec_type_input").each(function(i){
		if(specGalleryJson[this.value])
		{
			var specGallery = specGalleryJson[this.value];
			for(var j=0;j< specGallery.length;j++)
			{
				if(gallerys[specGallery[j].level])
					gallerys[specGallery[j].level].push(specGallery[j].gallery_id); 
				else
				{
					gallerys[specGallery[j].level] = new Array();
					gallerys[specGallery[j].level].push(specGallery[j].gallery_id); 
				}
			}	
		}
	});
	
	for(var attr in gallerys)
	{
		for(var i=0;i<gallerys[attr].length;i++)
		{
			var bln = true;
			for(var j=0;j<galleryIDs.length;j++)
			{
				if(galleryIDs[j] == gallerys[attr][i])
				{
					bln = false;
					break;
				}
			}
			if(bln)
				galleryIDs.push(gallerys[attr][i]);
		}
	}
	
	var html="";
	
	for(var i=0;i<galleryIDs.length;i++)
	{
		var gallery = galleryJson[galleryIDs[i]];
		if(i == 0)
		{
			html+="<li><a href='javascript:;' class='act' big='"+ROOT_PATH+gallery.big_img+"' origin='"+ROOT_PATH+gallery.origin_img+"'><img src='"+ROOT_PATH+gallery.small_img+"' /></a></li>";
			$("#big_img").empty();
			$("#big_img").html("<a href='"+ROOT_PATH+gallery.origin_img+"' class='jqzoom' title='"+goodsName+"'><img src='"+ROOT_PATH+gallery.big_img+"' alt='"+goodsName+"'/></a>");
			$(".jqzoom").jqzoom();
		}
		else
			html+="<li><a href='javascript:;' big='"+ROOT_PATH+gallery.big_img+"' origin='"+ROOT_PATH+gallery.origin_img+"'><img src='"+ROOT_PATH+gallery.small_img+"' /></a></li>";
	}
	
	if(html != "")
	{
		$(".small_img .gallery_list ul").empty();
		$(".small_img .gallery_list ul").html(html);
		$(".small_img .gallery_list").unbind();
		$(".small_img .gallery_list ul").unbind();
		$(".small_img .next").unbind();
		$(".small_img .prev").unbind();
		$(".small_img .gallery_list").jCarouselLite({
			btnNext: ".small_img .next",
			btnPrev: ".small_img .prev",
			visible: 5
		});
		
		$(".small_img .gallery_list a").hover(function(){
			$(".small_img .gallery_list a").removeClass("act");
			$(this).addClass("act");
			$("#big_img").empty();
			$("#big_img").html("<a href='"+this.getAttribute("origin")+"' class='jqzoom' title='"+goodsName+"'><img src='"+this.getAttribute("big")+"' alt='"+goodsName+"'/></a>");
			$(".jqzoom").jqzoom();
		},function(){});
	}
	
}


//开始规格的相关脚本
function setSpecScore(spec_id,obj)
{
	if (obj.className == 'act') {
		$(obj.parentNode).find(".spec_type_input").val(0);
	}
	else {
		$(obj.parentNode).find(".spec_type_input").val(spec_id);
	}
	getGalleryBySpec();
	cal_score();
}
function getPwdQuestion()
{
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=User&"+VAR_ACTION+"=getPwdQuestion&username="+$("#user_name").val(),
		  cache: false,
		  success:function (html){
				$("#pwd_question").html(html);				
			}
		});
}

//礼品选取
function choose_gift(goods_id,obj)
{
	var spec_item_id = $(obj.parentNode).find(".spec_item").val();
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=getGiftSpec&goods_id="+goods_id+"&spec_item_id="+spec_item_id,
		  cache: false,
		  success:function (html){	
		  			if (html != '') 
					{
						$(obj.parentNode).find(".spec_box").fadeIn();
						$(obj.parentNode).find(".spec_box").html(html);
					}
					else
					{
						$.ajax({
						  type: "POST", 	
						  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=getGiftSpecItem&goods_id="+goods_id+"&str=",
						  cache: false,
						  success:function (html){	
								var res = $.evalJSON(html);
								if(res.status)
								{
									$(obj.parentNode).find(".spec_item").val(res['id']);
									$(obj.parentNode).find(".spec_item").attr('checked',true);
								}
								else
								{
									alert(res['info']);
								}
							}	  
						});
					}
				}	  
		});
}
function setGiftSpec(spec_id,obj)
{
	$(obj.parentNode).find(".spec_type_input").val(spec_id);
	var spec_type_specs = $(".spec_type_input");

	for(var i=0;i<spec_type_specs.length;i++)
	{
		var btns = $(spec_type_specs[i].parentNode).find("a");
		for(var j=0;j<btns.length;j++)
		{
			
			if(btns[j].id == "spec_item_"+spec_type_specs[i].value)
			{
				$(btns[j]).addClass("act");
			}
			else
			{
				$(btns[j]).removeClass("act");
			}

		}
	}
}
function confirmGiftSpec(goods_id,obj)
{
	var spec_type_input = $(obj.parentNode.parentNode).find(".spec_type_row").find(".spec_type_input");
	var spec_conf_str = '';
	for(var i=0;i<spec_type_input.length;i++)
	{
		var spec_type_id = spec_type_input[i].id;
		spec_type_id = spec_type_id.split("_");
		spec_type_id = spec_type_id[1];
		spec_conf_str+=spec_type_id+"_"+spec_type_input[i].value+",";
	}
	spec_conf_str = spec_conf_str.substr(0,spec_conf_str.length-1);

	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=getGiftSpecItem&goods_id="+goods_id+"&str="+spec_conf_str,
		  cache: false,
		  success:function (html){	
				var res = $.evalJSON(html);
				if(res.status)
				{
					$(obj.parentNode.parentNode.parentNode).find(".spec_item").val(res['id']);
					$(obj.parentNode.parentNode.parentNode).find(".spec_item").attr('checked',true);
					$(obj.parentNode.parentNode).hide();
				}
				else
				{
					alert(res['info']);
				}
			}	  
		});

}
function cancelGiftSpec(obj)
{
	$(obj.parentNode.parentNode).fadeOut();
}
function cancel_gift(goods_id,obj)
{
	$(obj.parentNode).find(".spec_box").html("");
	$(obj.parentNode).find(".spec_item").val(0);
	$(obj.parentNode).find(".spec_item").attr('checked',false);
}

/**
 * 
 * 添加赠品
 */
function addGift(obj,promote_id)
{
	var cbos = $.find(".spec_item_"+promote_id);

	var spec_item_id = "";
	for(var i=0;i<cbos.length;i++)
	{
		if(cbos[i].checked)
		{
			spec_item_id+=cbos[i].value+",";
		}
	}
	if(spec_item_id.length>0)
	spec_item_id = spec_item_id.substr(0,spec_item_id.length-1);
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Cart&"+VAR_ACTION+"=addGift&spec_item_ids="+spec_item_id+"&promote_id="+promote_id,
		  cache: false,
		  success:function (html){	
				if(html!='')
				{
					$("#cart_promote_goods_list").html(html);
				}
			}	  
		});
}
//使用优惠券
function useCard(obj)
{
	var card_code = $("#promote_card").val();
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Cart&"+VAR_ACTION+"=useCard&card_code="+card_code,
		  cache: false,
		  success:function (html){	
				if(html!='')
				{
					var rs = $.evalJSON(html);
					if(rs.status==0)
					{
						alert(rs.info);
					}
					else
					{
						location.href = location.href;
					}
				}
				
			}	  
		});
}

function exchangeCard(card_id,msg)
{
	if(confirm(msg))
	{
		$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=UcPromoteCard&"+VAR_ACTION+"=exchange&card_id="+card_id,
		  cache: false,
		  success:function (html){	
			   var data = $.evalJSON(html);
			   if(data.status)
			   {
			   		alert(data.info);
			   }
			   else
			   {
			   		alert(data.info);
			   }
				
			}	  
		});
	}
}

function initAjaxLoader()
{
	 $("#ajax_loader").ajaxStart(function(){
	 		$(this).show();
	 }); 
	 $("#ajax_loader").ajaxStop(function(){
			$(this).hide();
	 });
}

//判断s是否为数字
function isdigit(s)
{
	//alert(typeof(s))
	if (typeof(s)=='string'){
		var r,re;
		re = /\d*/i;    //\d表示数字,*表示匹配多个数字
		r = s.match(re);
		
		return (r==s)?1:0;
	}else{
		return 0;
	}

}

//将字符串转化为数字，不是数字字符串的则返回为：0
function strToFloat(s){
	var r = parseFloat(s);
	if (isNaN(r)){
		return 0;
	}else{
		return r;
		//return round(r, precision);
	}	
}

function round(thisNumber,n){//四舍五入
	thisNumber = strToFloat(thisNumber);
	return Math.round(thisNumber*Math.pow(10,n))/Math.pow(10,n); 
}


function list_type(type)
{
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Ajax&"+VAR_ACTION+"=changeListType&type="+type,
		  cache: false,
		  success:function (html){	
			  location.href = location.href;				
			}	  
		});
}

function initCompare()
{
	$( document ).ready( function ()
			{
				$( '#compare_box' ).scrollFollow(
					{
						speed: 1000,
						offset: 60,
						killSwitch: 'exampleLink',
						onText: 'Disable Follow',
						offText: 'Enable Follow'
					}
				);
			}
		);
}
function addCompare(goods_id)
{
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=addCompare&id="+goods_id,
		  cache: false,
		  success:function (html){	
				var rs = $.evalJSON(html);
				if(rs.status)
				{
					$("#compare_box").html(rs.html);
					$("#compare_box").show();
				}
				else
				{
					alert(rs.html);
				}
			}	  
		});
	
}

function delCompare(goods_id)
{
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=delCompare&id="+goods_id,
		  cache: false,
		  success:function (html){	
				if(html)
				{
					$("#compare_box").html(html);
					$("#compare_box").show();
				}
				else
				{
					$("#compare_box").hide();
				}
			}	  
		});
	
}

function clearHistory()
{
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=clearHistory",
		  cache: false,
		  success:function (html){	
			  location.href = location.href;				
			}	  
		});	
}

function collect(rec_module,rec_id)
{
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?"+VAR_MODULE+"=Collect&"+VAR_ACTION+"=doCollect&rec_module="+rec_module+"&rec_id="+rec_id,
		  cache: false,
		  success:function (html){	
			 var data = $.evalJSON(html);
			 if(data.status == 0 || data.status == 1|| data.status == 3)
			 {
				 alert(data.msg);
			 }
			 else if(data.status == 2)
			 {
				 alert(data.msg);	
				 location.href = ROOT_PATH+"/index.php?"+VAR_MODULE+"=User&"+VAR_ACTION+"=login";
			 }
			
				 
		  }	  
		});		
}

function swsubmit()
{
	
	if($("#cityname_box").css("display")=='none')
	{
		$("#cityname_box").show();
		$("#citylist_box").hide();
		$("#switchbtn").html(LANG.JS_SELEST_LIST);
	}
	else
	{
		$("#citylist_box").show();
		$("#cityname_box").hide();
		$("#switchbtn").html(LANG.JS_OTHER_CITY);
	}
}


function close_top_adv()
{
	$("#top_ad").slideUp();	
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/index.php?m=Ajax&a=close_top_adv",
		  cache: false,
		  success:function (html){	
						 
		  }	  
		});			
}

function imagesInit()
{
	imgListCount = $('#img_list a').size();
	
	if(imgListCount < 2)
		return;
	
	imgListInterval = setInterval('imagesRun()',3000);
	 
	$('#goods_imgs li,#img_list a').hover(function(){
		clearInterval(imgListInterval);
	},function(){
		imgListInterval = setInterval('imagesRun()',3000);
	});
	 
	$('#img_list a').click(function(){
		var index = $('#img_list a').index(this);
		if (imgListCurr != index){
			imagesPlay(index);
            imgListCurr = index;
        };
		return false;
    });
}

function imagesRun()
{
	imgListNext = imgListCurr + 1;
    if (imgListCurr == imgListCount - 1)
		imgListNext = 0;
		
	imagesPlay(imgListNext);
	
	imgListCurr++;
	
    if (imgListCurr > imgListCount - 1)
	{
		imgListCurr = 0;
		imgListNext = imgListCurr + 1;
	}
}

function imagesPlay(next)
{
	$('#goods_imgs li').eq(imgListCurr).css({'opacity':'0.5'}).animate({'left':'-440px','opacity':'1'},'slow',function(){
		$(this).css({'left':'440px' });
	}).end().eq(next).animate({'left':'0px','opacity':'1'},'slow',function(){
		$('#img_list a').siblings('a').removeClass('active').end().eq(next).addClass('active');
	});
}
function reset_delivery(){
	$("input[name='delivery']").attr("checked",false);
	$("input[name='protect']").attr("checked",false);
	$("input[name='protect']").attr("disabled",true);
	$(".consignee-box").hide();
	countCartTotal();
}
//add by chenfq 2010-10-20 读取商品销售数量
function get_buy_count(id, buy_count)
{
	$("#buy_count_goods_id_"+id).html(buy_count);
	$.ajax({
		  type: "POST", 	
		  url: ROOT_PATH+"/services/ajax.php?run=buy_count&id="+id,
		  cache: false,
		  dataType:"txt",
		  success:function (html){	
			$("#buy_count_goods_id_"+id).html(html);		 
		  },
		  error:function(a,b,c)
			{
			  $("#buy_count_goods_id_"+id).html(buy_count);
			}  
		});			
}

//add by chenfq 2010-12-15 
function showtooltip(tooltip){
	if(tooltip && tooltip != 'undefined' && tooltip.length >30)
		$("#hdw").after(tooltip);
}

function showloginstatus(loginstatus){
	if(loginstatus && loginstatus != 'undefined')
	{
		$("#account").html(loginstatus);
	}else{
		$("#account").html('');
	}
}

function getcartinfo()
{
	if($("#FW_GOODS_COUNT") || $("#FW_TOTAL_PRICE"))
	{
		$.ajax({
			url : ROOT_PATH+"/index.php?m=Ajax&a=getcartinfo",
			cache: false,
			dataType: "json",
			success:function(data)
			{
				$("#FW_GOODS_COUNT").html(data.CARTNUM);
				$("#FW_TOTAL_PRICE").html(data.CARTTOTAL);
			}
		 });
	}
}

function copy_text(id)
	{
		$.copyText('#share-copy-text-'+id);
	}

function init_citysCur()
{
	$("#guides-city-list ul.P_CITYS li a").each(function(){
		if($("#guides-city-list ul.SUB_CITYS li.current a").attr("rel")==$(this).attr("rel"))
		{
			$(this).parent().addClass("current");
		}
	});
		
}

//添加团购，属性的加载 add by hjt 2013-3-29
function getAttrList(obj)
{
	var type_id = obj.value;
	var supplier_goods_id = document.getElementById("supplier_goods_id")? document.getElementById("supplier_goods_id").value:0;	
	
	$.ajax({
		  url: ROOT_PATH+"/index.php?m=Ajax&a=gettypeattr&type_id="+type_id+"&supplier_goods_id="+supplier_goods_id,
		  cache: false,
		  dataType:'json',
		  success:function(data)
		  {		
			  var html = "";
			  var lang_ids_array = lang_ids.split(',');
			  var lang_names_array = lang_names.split(',');
			  for(var i=0;i<data.length;i++)
			  {
				  if(data[i].row_count==0)
				  {
					 m=0;
					 html+= "<div style='padding-bottom:5px;'>"+data[i]['name_'+default_lang_id]+":";
					  if(data[i]['input_type']=='0')
					  {
						  //列表
						  for(var k=0;k<lang_ids_array.length;k++)
						  {
							  html+="<select name='attr_value["+data[i]['id']+"]["+lang_ids_array[k]+"][]'>";

							  for(var j=0;j<data[i]['attr_value_'+lang_ids_array[k]].length;j++)
							  {
								  html+="<option value='"+data[i]['attr_value_'+lang_ids_array[k]][j]+"'";
								  var c_val ='';
								  if(data[i]['value_'+lang_ids_array[k]]!=undefined)
								  {
									  c_val = data[i]['value_'+lang_ids_array[k]][m];
								  }
								 
								  if(c_val==data[i]['attr_value_'+lang_ids_array[k]][j])
								  {
									  html+=" selected='selected' ";
								  }
								  html+=" >"+data[i]['attr_value_'+lang_ids_array[k]][j]+"</option>";
							  }
							  html+="</select>&nbsp;("+lang_names_array[k]+")&nbsp;";
						  }
				
					  }
					  else
					  {
						  for(var k=0;k<lang_ids_array.length;k++)
						  {
							  var c_val ='';
							  if(data[i]['value_'+lang_ids_array[k]]!=undefined)
							  {
								  c_val = data[i]['value_'+lang_ids_array[k]][m];
							  }
							  html+="<input type='text' value='"+c_val+"' name='attr_value["+data[i]['id']+"]["+lang_ids_array[k]+"][]' />&nbsp;("+lang_names_array[k]+")&nbsp;";
						  }
						  
					  }
					  
					  
					  //if(data[i]['attr_type']==1)
					  if(true)
					  {
						  var p_val ='';
						  if(data[i]['price']!=undefined)
						  {
							  p_val = data[i]['price'][m];
						  }
						  html+=ATTR_PRICE+"：<input type='text' name='attr_price["+data[i]['id']+"][]' value='"+p_val+"' />";
						  /*
						  var s_val ='';
						  if(data[i]['stock']!=undefined)
						  {
							  s_val = data[i]['stock'][m];
						  }
						  if(s_val&&s_val==1)
							  html+=ATTR_STOCK+"：<input type='checkbox' value='1' checked='checked' onchange='setStockVal(this);' /><input type='hidden' value='1' name='attr_stock["+data[i]['id']+"][]'  />";
						  else
							  html+=ATTR_STOCK+"：<input type='checkbox' value='1' onchange='setStockVal(this);'  /> <input type='hidden' value='0' name='attr_stock["+data[i]['id']+"][]'  />";
						  */
						  if(m==0)
						  html+="[<a href='javascript:;' onclick='addAttrInputRow(this);'>+</a>]";
						  else
						  html+="[<a href='javascript:;' onclick='delAttrInputRow(this);'>-</a>]";
					  }
					  
					  html+="</div>";
				  }
				  else
				  {
					  for(var m=0;m<data[i].row_count;m++)
					  {
					  html+= "<div style='padding-bottom:5px;'>"+data[i]['name_'+default_lang_id]+":";
					  if(data[i]['input_type']=='0')
					  {
						  //列表
						  for(var k=0;k<lang_ids_array.length;k++)
						  {
							  html+="<select name='attr_value["+data[i]['id']+"]["+lang_ids_array[k]+"][]'>";
							  for(var j=0;j<data[i]['attr_value_'+lang_ids_array[k]].length;j++)
							  {
								  html+="<option value='"+data[i]['attr_value_'+lang_ids_array[k]][j]+"'";
								  var c_val ='';
								  if(data[i]['value_'+lang_ids_array[k]]!=undefined)
								  {
									  c_val = data[i]['value_'+lang_ids_array[k]][m];
								  }
								  
								  if(c_val==data[i]['attr_value_'+lang_ids_array[k]][j])
								  {
									  html+=" selected='selected' ";
								  }
								  html+=" >"+data[i]['attr_value_'+lang_ids_array[k]][j]+"</option>";
							  }
							  html+="</select>&nbsp;("+lang_names_array[k]+")&nbsp;";
						  }
				
					  }
					  else
					  {
						  for(var k=0;k<lang_ids_array.length;k++)
						  {
							  var c_val ='';
							  if(data[i]['value_'+lang_ids_array[k]]!=undefined)
							  {
								  c_val = data[i]['value_'+lang_ids_array[k]][m];
							  }
							  html+="<input type='text' value='"+c_val+"' name='attr_value["+data[i]['id']+"]["+lang_ids_array[k]+"][]' />&nbsp;("+lang_names_array[k]+")&nbsp;";
						  }
						  
					  }
					  
					  
					  //if(data[i]['attr_type']==1)
					  if(true)
					  {
						  var p_val ='';
						  if(data[i]['price']!=undefined)
						  {
							  p_val = data[i]['price'][m];
						  }
						  html+= ATTR_PRICE+"：<input type='text' name='attr_price["+data[i]['id']+"][]' value='"+p_val+"' />";
						  /*
						  var s_val ='';
						  if(data[i]['stock']!=undefined)
						  {
							  s_val = data[i]['stock'][m];
						  }
						  if(s_val&&s_val==1)
							  html+=ATTR_STOCK+"：<input type='checkbox' value='1' checked='checked' onchange='setStockVal(this);' /><input type='hidden' value='1' name='attr_stock["+data[i]['id']+"][]'  />";
						  else
							  html+=ATTR_STOCK+"：<input type='checkbox' value='1' onchange='setStockVal(this);'  /> <input type='hidden' value='0' name='attr_stock["+data[i]['id']+"][]'  />";
						  */
						  if(m==0)
						  html+="[<a href='javascript:;' onclick='addAttrInputRow(this);'>+</a>]";
						  else
						  html+="[<a href='javascript:;' onclick='delAttrInputRow(this);'>-</a>]";
					  }
					  else
					  {
						  if(data[i]['input_type']==1)
						  {
							  html+="("+ATTR_TIPS+")";
						  }
					  }
					  
					  html+="</div>";
					  } //end row_count loop
				  } // end if count
			  }
			  if(html){
				  $("#attr_list").parent().css({"display":"block"});
			  }else{
				  $("#attr_list").parent().css({"display":"none"});
			  }
			  $("#attr_list").html(html);
		  }
		}); 
}
function addAttrInputRow(obj)
{
	var contentbox = obj.parentNode;
	var innerhtml = contentbox.innerHTML;
	innerhtml = innerhtml.replace(/addAttrInputRow/g,"delAttrInputRow");
	innerhtml = innerhtml.replace(/\+/g,"-");	
	var html = "<div style='padding-bottom:5px;'>"+innerhtml+"</div>";
	$(contentbox).after(html);
}

function delAttrInputRow(obj)
{
	var contentbox = obj.parentNode;
	$(contentbox).remove();
}