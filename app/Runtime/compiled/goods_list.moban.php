<style type="text/css">
	#content{width:916px;border: 1px solid #EEC882;background:#fff;padding:15px;}
	.pages{
            text-align: right;
            float: right;
            padding-left: 10px;
            height: 28px;
            line-height: 28px;
        }
	.pages a{border:#CCC 1px solid;font-weight:bold; padding:5px;}
	.pages a:hover{border:#e5e5e5 1px solid;background:#f7f7f7;text-decoration:none;color:#2d2d2d}
	.pages a:active{border:#FF6600 1px solid;}
	.pages span{padding:5px 9px;border: #E5E5E5 1px solid;background: #EFEFEF;font-weight:bold;}
	.order_by{font-size:12px;}
	.order_by a{color:#CD1A01;background:#fff;line-height: 26px;text-decoration:none;}
	.order_by a.cur,.order_by a:hover{color:#fff; background:#CD1A01}
        a.chalook:hover{
            background: none repeat scroll 0 0 #CD1A01;
            color: #ffffff;
            font-size: 12px;
            test-decoration:none;
        }
        .price_past span.buy_count{
            color: #C51800;
            font-size: 20px;
        }
        .but_count_1{
            font: 12px Tahoma,Helvetica,Arial,Simsun,sans-serif;
            font-weight: bold;
            padding-top: 6px;
        }
        .price_1{
            float: left;
            font-size: 12px;
            margin-top: -6px;
        }
</style>

<div id="content">
			
    <div class="order_by">
        <dl>
            <dt ><strong >排序</strong></dt>
            <dd style="width:860px;">
                <ul style="width:140px; float:left;">
                    <li><a  href="<?php echo getNewURL($_REQUEST['m']."/".$_REQUEST['a'],$_REQUEST['id'],$_REQUEST['qid'],$_REQUEST['gp'],"default"); ?>" <?php if (! $_REQUEST['sc'] || $_REQUEST['sc'] == 'default'): ?>style="color:#fff;background:#CD1A01"<?php endif; ?>>智能</a></li>
                    <li><a  href="<?php echo getNewURL($_REQUEST['m']."/".$_REQUEST['a'],$_REQUEST['id'],$_REQUEST['qid'],$_REQUEST['gp'],"new"); ?>"   <?php if ($_REQUEST['sc'] == 'new'): ?>style="color:#fff;background:#CD1A01"<?php endif; ?>>最新</a> </li>
                    <li><a  href="<?php echo getNewURL($_REQUEST['m']."/".$_REQUEST['a'],$_REQUEST['id'],$_REQUEST['qid'],$_REQUEST['gp'],"sell"); ?>"  <?php if ($_REQUEST['sc'] == 'sell'): ?>style="color:#fff;background:#CD1A01"<?php endif; ?>>最具人气</a></li>
                </ul>
               <?php echo $this->_var['pages']; ?>
           <div style="float:left;padding-left: 10px;">
            <ul>
                <li> 查看:</li>
                <li>
                    <?php if ($_REQUEST['index_type'] == "old"): ?>
                        <a class="chalook" href="?m=Index&a=index&index_type=new">使用时尚版</a>
                        <?php else: ?>
                    <a class="chalook" href="?m=Index&a=index&index_type=old">使用经典版</a>
                    <?php endif; ?>
                </li>
                <!--<li>|</li>
                <li>
                    <?php if ($_REQUEST['index_type'] == "all"): ?>
                       <a class="chalook"  href="?m=Index&a=index&index_type=new"> 显示分页</a>
                      <?php else: ?>
                      <a class="chalook"  href="?m=Index&a=index&index_type=all"> 显示所有团购</a>
                      <?php endif; ?>
                </li>-->
             </ul>         
        </div>
		<div class="clear"></div>
       </dd>
		<div class="clear"></div>
        </dl>
    </div>
        
        
        
	<div class="blank" style="height:8px"></div>
	<div class="blank" style="background:#fff;border-top:1px solid #ccc;height:6px"></div>
	<div class="index-goods-list">
	<dl  class="cf">
	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['goods_l'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods_l']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['goods_l']['iteration']++;
?>
		<dd class="cf" <?php if ($this->_foreach['goods_l']['iteration'] % 3 == 0): ?>style="border:none"<?php endif; ?>>
			<div class="box">
				<div class="img cf">
					<a href="<?php echo $this->_var['goods']['url']; ?>" title="<?php echo $this->_var['goods']['name_1']; ?>" target="_blank"><img src="<?php echo $this->_var['CND_URL']; ?><?php echo $this->_var['goods']['small_img']; ?>" /></a>
					<div class="price_past">
                                            <div class="but_count_1">
                                                <span class="buy_count"><?php echo $this->_var['goods']['buy_count']; ?></span>人购买
                                            </div>
                                            <div class="price_1">
                                                 原价: <del style="font-family: Arial; font-size: 14px;margin-right: 10px;"><?php 
$k = array (
  'name' => 'a_formatPrice',
  'a' => $this->_var['goods']['market_price'],
);
echo $k['name']($k['a']);
?> </del>
                                                 折扣: <span style="font-weight: bold; font-size: 14px;" class="orange"> <?PHP echo sprintf(a_L("XY_SHOP_SAVE_POINT"),$this->_var['goods']['discountfb']);?></span>
						
                                            </div>
						
						
					</div>
				</div>
				<div class="clear"></div>
				<div class="infos <?php if ($this->_var['goods']['is_none']): ?>info_no<?php endif; ?>">
					<a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><strong><?php echo $this->_var['goods']['shop_price']; ?></strong>
					</a>
				</div>
			</div>
			
			<h3>
                            【<b><a href="<?php echo a_u('Index/index','cityname-'.$this->_var['goods']['city']['py']);?>"><?php echo $this->_var['goods']['city']['name']; ?></a></b>】
                        <a href="<?php echo $this->_var['goods']['url']; ?>" title="<?php echo $this->_var['goods']['name_1']; ?>" target="_blank"><?php if ($this->_var['goods']['expand3']): ?><?php echo $this->_var['goods']['expand3']; ?><?php else: ?><?php 
$k = array (
  'name' => 'a_msubstr',
  'a' => $this->_var['goods']['name_1'],
  'b' => '0',
  'c' => '30',
);
echo $k['name']($k['a'],$k['b'],$k['c']);
?><?php endif; ?></a>
                    </h3>
		</dd>
		<?php if ($this->_foreach['goods_l']['iteration'] % 3 == 0): ?>
                        <dd class="blank" style="background:#fff;clear:both;width:100%;height:6px;border:none;margin:0;padding:0;"></dd>
			<dd class="blank" style="background:#fff;clear:both;width:100%;height:6px;border:none;margin:0;padding:0;border-top:1px solid #ccc"></dd>
                        <?php elseif (($this->_foreach['goods_l']['iteration'] == $this->_foreach['goods_l']['total'])): ?>
                        <dd class="blank" style="background:#fff;clear:both;width:100%;height:6px;border:none;margin:0;padding:0;"></dd>
			<dd class="blank" style="background:#fff;clear:both;width:100%;height:6px;border:none;margin:0;padding:0;border-top:1px solid #ccc"></dd>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
       
	</dl>
            
	</div>
        
	<div class="clear"></div><?php echo $this->_var['pages']; ?>
</div>
<script type="text/javascript">
	function share_url(id)
	{

		if ($("#deal-share-im-c-"+id).css("display") == "none") {			
			$("#share-copy-text-"+id).val($("#share_url_"+id).val());
			$("#deal-share-im-c-"+id).show();
		}
		else 
			$("#deal-share-im-c-"+id).hide();
	}
	
	
	
var updEndNowTime = <?PHP echo (a_gmtTime()+ (intval(a_fanweC("TIME_ZONE"))*3600))."000";?>;
function updateEndTime()
{
	var time = updEndNowTime;
	$(".counter").each(function(i){
		var endDate =new Date(this.getAttribute("endTime"));
		var endTime = endDate.getTime();
		var lag = (endTime - time) / 1000;
		if(lag > 0)
		{
			var second = Math.floor(lag % 60);     
			var minite = Math.floor((lag / 60) % 60);
			var hour = Math.floor((lag / 3600) % 24);
			var day = Math.floor((lag / 3600) / 24);
			
			var timeHtml = "<b>"+hour+"</b><span>"+LANG.JS_HOUR+"</span><b>"+minite+"</b><span>"+LANG.JS_MINUTE+"</span>";
						if(day > 0)
							timeHtml ="<b>"+day+"</b><span>"+LANG.JS_DAY+"</span>" + timeHtml;
							
						timeHtml+="<b>"+second+"</b><span>"+LANG.JS_SECOND+"</span>";
			$(this).html(timeHtml);
		}
		else
			$(this).html("<?php echo $this->_var['lang']['XY_GROUP_IS_END']; ?>");
	});
	updEndNowTime+=1000;
	setTimeout("updateEndTime()",1000);
}
updateEndTime();

</script>