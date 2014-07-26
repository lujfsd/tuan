<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Generator" content="Fanwe v<?php echo $this->_var['CFG']['SYS_VERSION']; ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->_var['site_info']['title']; ?></title>
<meta name="keywords" content="<?php echo $this->_var['site_info']['keyword']; ?>" />
<meta name="description" content="<?php echo $this->_var['site_info']['content']; ?>" />
<link rel="icon" href="favicon.ico" type="/image/x-icon" />
<link rel="shortcut icon" href="favicon.ico" type="/image/x-icon" />

<link rel="stylesheet" type="text/css" href="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/css/style.css" />
<script type="text/javascript" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/js/jcarousellite.js"></script>
<script type="text/javascript" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/js/script.js"></script>
<script type="text/javascript" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/js/jquery.pngFix.js"></script>
<script type="text/javascript" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/js/jquery.lazyload.pack.js"></script>
<script type="text/javascript" src="<?php echo $this->_var['CND_URL']; ?>/app/Runtime/js_lang.js"></script>
<script language="JavaScript">
<!--
//指定当前组模块URL地址 
var CND_URL = '<?php echo $this->_var['CND_URL']; ?>';
var HTTP_URL = '<?php echo $this->_var['HTTP_URL']; ?>';
var ROOT_PATH = '<?php echo $this->_var['__ROOT__']; ?>';
var PUBLIC = '<?php echo $this->_var['TMPL_PATH']; ?>Public';
var VAR_MODULE = 'm';
var VAR_ACTION = 'a';
var FANWE_LANG_ID = '1';
var cityID = '<?php echo $this->_var['currentCity']['id']; ?>';
$(document).ready(function(){
	$("#contentW img,#content img").lazyload({ 
				placeholder : "<?php echo $this->_var['CND_URL']; ?>/Public/img_loading.gif",
				failurelimit : 10
		 });
});

//-->
</script>

</head>
<body class="bg-alt">
<div id="doc">
	<div id="hdw">
		<div class="head">
        <div id="hd">
            <div id="logo">
				<a class="link" href="<?php 
$k = array (
  'name' => 'a_fanweC',
  'value' => 'SHOP_URL',
);
echo $k['name']($k['value']);
?>"><img src="<?php echo $this->_var['CND_URL']; ?><?php 
$k = array (
  'name' => 'a_fanweC',
  'value' => 'SHOP_LOGO',
);
echo $k['name']($k['value']);
?>" /></a>
			</div>
			<?php if(count($this->_var['city_list']) >1){ ?>
	            <div class="guides">
	                <div class="city">
	                    <h2><?php echo $this->_var['currentCity']['name']; ?></h2>
	                </div>
	                <div class="change"><a href="<?php 
$k = array (
  'name' => 'a_u',
  'value' => 'City/more',
);
echo $k['name']($k['value']);
?>"><?php echo $this->_var['lang']['XY_CHANGE_CITY']; ?></a></div>
					<div class="wek"><?php echo $this->_var['SHOP_NAME']; ?>已开通城市<?php
					$citys = $this->_var['city_list'];
					$ci = 0;
					foreach($citys as $v)
					{
						if($v['status']==1)
						{
							++$ci;
						}
					}
					echo $ci;
					?></div>
	            </div>
            <?php } ?>
            <ul class="nav cf">
				<?php $_from = $this->_var['main_navs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['nav']):
?>
				<li><a href="<?php echo $this->_var['nav']['url']; ?>"  target="<?php echo $this->_var['nav']['target']; ?>" <?php if ($this->_var['nav']['act'] == 1): ?>class="current"<?php endif; ?>><?php echo $this->_var['nav']['name_1']; ?></a></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
          
            <div class="logins">
			<?php 
$k = array (
  'name' => 'member_info',
);
echo $this->_hash . $k['name'] . '|' . serialize($k) . $this->_hash;
?>
            </div>
			<ul id="myaccount-menu">
			<?php $_from = $this->_var['user_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'menu_item');if (count($_from)):
    foreach ($_from AS $this->_var['menu_item']):
?>
			      	<li><a href="<?php echo $this->_var['menu_item']['url']; ?>"><?php echo $this->_var['menu_item']['name']; ?></a></li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
			
			<div class="refer">
			&raquo;&nbsp;<a href="<?php 
$k = array (
  'name' => 'a_u',
  'a' => 'Cart/index',
);
echo $k['name']($k['a']);
?>">购物车</a>&nbsp;&raquo;&nbsp;<a href="<?php echo a_u("Referrals/index","id-".intval($this->_var['goods']['id'])."|tid-".intval($this->_var['tid'])) ?>"><?php echo $this->_var['lang']['XY_REFERRALS']; ?> <?php echo $this->_var['referralsMoney']; ?> </a>
			</div>
			
			<div class="deal-subscribe">
				<div id="deal-subscribe-body">
					<FORM id="deal-subscribe-form" method="post" action="<?php echo $this->_var['__ROOT__']; ?>/index.php">
					<TABLE class="address">
						<TBODY>
							<tr><td colspan="2">想知道每天的的团购是什么？[<a href='javascript:;' class='unsubScribeBtn'>退订</a>]</td></tr>
							<TR>
								<TD><input class="f-text" name="email"></TD>
								<TD><input value="<?php echo $this->_var['currentCity']['id']; ?>" type="hidden" name="cityid">
									<input value="<?php echo $this->_var['lang']['XY_SUBSCRIBE']; ?>" type="submit" class="btn"></TD>
							</TR>
							<TR>
								<TD>
								<?php if ($this->_var['CFG']['SMS_SUBSCRIBE'] == 1): ?>
								<a href="javascript:;" id="smsSubscribe">» <?php echo $this->_var['lang']['XY_SMS_SUBSCRIBE']; ?></a>&nbsp;&nbsp;
								<a href="javascript:;" id="unSmsSubscribe">» <?php echo $this->_var['lang']['XY_SMS_UNSUBSCRIBE']; ?></a>&nbsp;&nbsp;
								<?php endif; ?></TD>
							</TR>
						</TBODY>
						<input value="Index" type="hidden" name="m" />
						<input value="malllist" type="hidden" name="a">
						<input value="subScribe" type="hidden" name="do">
					</TABLE>
					</FORM>
				</div>
			</div>
			
        </div>
		</div>
    </div>
  
	<?php 
$k = array (
  'name' => 'getGoodsStatus',
  'id' => $this->_var['goods']['id'],
);
echo $this->_hash . $k['name'] . '|' . serialize($k) . $this->_hash;
?>
	<?php 
$k = array (
  'name' => 'getTooltipStatus',
  'id' => $this->_var['goods']['id'],
);
echo $this->_hash . $k['name'] . '|' . serialize($k) . $this->_hash;
?>
	<?php 
$k = array (
  'name' => 'advLayout',
  'id' => '顶部通栏广告',
  'file' => 'adv/top_adv.moban',
);
echo $this->_hash . $k['name'] . '|' . serialize($k) . $this->_hash;
?>
	
	<?php if (( $_REQUEST['m'] == 'Index' && $_REQUEST['a'] == 'index' ) || ( $_REQUEST['m'] == 'Goods' && $_REQUEST['a'] == 'showcate' )): ?>
	<div class="goods-cate-hd-list">
	 <?php if ($this->_var['sidegoodscatelist']): ?>
		<div class="goods_cate">
		<span class="cate_title">分类：</span>
		<span <?php if ($this->_var['pid'] == 0): ?>class="current"<?php endif; ?>><a href="<?php 
$k = array (
  'name' => 'a_u',
  'value' => 'Index/index',
);
echo $k['name']($k['value']);
?>">全部</a></span>		
		<?php $_from = $this->_var['sidegoodscatelist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'gclist');$this->_foreach['gclist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['gclist']['total'] > 0):
    foreach ($_from AS $this->_var['gclist']):
        $this->_foreach['gclist']['iteration']++;
?>
			<span <?php if ($this->_var['pid'] == $this->_var['gclist']['id']): ?>class="current"<?php endif; ?>>
				<a href="<?php echo $this->_var['gclist']['url']; ?>" title="<?php echo $this->_var['gclist']['name']; ?>" shopping="food"><?php echo $this->_var['gclist']['name']; ?>[<?php echo $this->_var['gclist']['goods_count']; ?>]</a>
			</span>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</div>
		<?php if ($this->_var['sub_cate_list']): ?>
			<div class="cate_sub">
				<span <?php if ($this->_var['catepid'] == $this->_var['pid']): ?>class="current"<?php endif; ?>><a href="<?php echo a_u("Goods/showcate","id-".intval($this->_var['pid']))?>">全部</a></span>
				<?php $_from = $this->_var['sub_cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sub_item');$this->_foreach['sub_item'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sub_item']['total'] > 0):
    foreach ($_from AS $this->_var['sub_item']):
        $this->_foreach['sub_item']['iteration']++;
?>
			     <span <?php if ($this->_var['catepid'] == $this->_var['sub_item']['id']): ?>class="current"<?php endif; ?>>
                     <a href="<?php echo $this->_var['sub_item']['url']; ?>" title="<?php echo $this->_var['sub_item']['name']; ?>" shopping="food" <?php if ($this->_var['sub_item']['id'] == $this->_var['catepid']): ?>class="current"<?php endif; ?>><?php echo $this->_var['sub_item']['name']; ?>(<?php echo $this->_var['sub_item']['goods_count']; ?>)</a>
				 </span>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div> 
		<?php endif; ?>
	 <?php endif; ?>
	 
	 <?php if ($this->_var['quan_list']): ?>
		<div class="shq">
			<span class="cate_title">商圈：</span>
			<span <?php if (intval ( $this->_var['top_pid'] ) == 0): ?>class="current"<?php endif; ?>>
					<a href="<?php echo getNewURL($_REQUEST['m']."/".$_REQUEST['a'],$_REQUEST['id'],0,$_REQUEST['gp'],$_REQUEST['sc']); ?>">全部</a>
			</span>		
			<?php $_from = $this->_var['quan_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'quan');$this->_foreach['quan'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['quan']['total'] > 0):
    foreach ($_from AS $this->_var['quan']):
        $this->_foreach['quan']['iteration']++;
?>
			<span <?php if (intval ( $this->_var['top_pid'] ) == intval ( $this->_var['quan']['id'] )): ?>class="current"<?php endif; ?>>
				<a href="<?php echo $this->_var['quan']['url']; ?>" title="<?php echo $this->_var['quan']['name']; ?>" shopping="food"><?php echo $this->_var['quan']['name']; ?>[<?php echo $this->_var['quan']['goods_count']; ?>]</a>
			</span>			
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</div>
		<?php if ($this->_var['sub_quan_list']): ?>
		    <div class="cate_sub" style="margin-bottom:3px;">
				<span <?php if (intval ( $this->_var['quan_id'] ) == intval ( $this->_var['top_pid'] )): ?>class="current"<?php endif; ?>>
						<a href="<?php echo getNewURL($_REQUEST['m']."/".$_REQUEST['a'],$_REQUEST['id'],intval($this->_var['top_pid']),$_REQUEST['gp'],$_REQUEST['sc']); ?>">全部</a>
				</span>	
				<?php $_from = $this->_var['sub_quan_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'quan');$this->_foreach['quan'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['quan']['total'] > 0):
    foreach ($_from AS $this->_var['quan']):
        $this->_foreach['quan']['iteration']++;
?>
				<span <?php if (intval ( $this->_var['quan_id'] ) == intval ( $this->_var['quan']['id'] )): ?>class="current"<?php endif; ?>>
					<a href="<?php echo $this->_var['quan']['url']; ?>" title="<?php echo $this->_var['quan']['name']; ?>" shopping="food"><?php echo $this->_var['quan']['name']; ?>[<?php echo $this->_var['quan']['goods_count']; ?>]</a>
				</span>			
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
		<?php endif; ?>
	 <?php endif; ?>
 		<div class="hd_search">
 			<div class="price">
 				<span class="cate_title span_left">价格区间：</span>
				<a  class="<?php if ($_REQUEST['gp'] == 'all'): ?>current<?php endif; ?>" href="<?php echo getNewURL($_REQUEST['m']."/".$_REQUEST['a'],$_REQUEST['id'],$_REQUEST['qid'],"all",$_REQUEST['sc']); ?>">全部</a>
				<a  class="<?php if ($_REQUEST['gp'] == '1'): ?>current<?php endif; ?>" href="<?php echo getNewURL($_REQUEST['m']."/".$_REQUEST['a'],$_REQUEST['id'],$_REQUEST['qid'],"1",$_REQUEST['sc']); ?>">100以内</a>
				<a  class="<?php if ($_REQUEST['gp'] == '2'): ?>current<?php endif; ?>" href="<?php echo getNewURL($_REQUEST['m']."/".$_REQUEST['a'],$_REQUEST['id'],$_REQUEST['qid'],"2",$_REQUEST['sc']); ?>">100~200</a>
				<a  class="<?php if ($_REQUEST['gp'] == '3'): ?>current<?php endif; ?>" href="<?php echo getNewURL($_REQUEST['m']."/".$_REQUEST['a'],$_REQUEST['id'],$_REQUEST['qid'],"3",$_REQUEST['sc']); ?>">200~300</a>
				<a  class="<?php if ($_REQUEST['gp'] == '5'): ?>current<?php endif; ?>" href="<?php echo getNewURL($_REQUEST['m']."/".$_REQUEST['a'],$_REQUEST['id'],$_REQUEST['qid'],"5",$_REQUEST['sc']); ?>">300~500</a>
				<a  class="<?php if ($_REQUEST['gp'] == 'gt5'): ?>current<?php endif; ?>" href="<?php echo getNewURL($_REQUEST['m']."/".$_REQUEST['a'],$_REQUEST['id'],$_REQUEST['qid'],"gt5",$_REQUEST['sc']); ?>">500以上</a>
 			</div>
			<form action="<?php 
$k = array (
  'name' => 'a_u',
  'value' => 'Goods/other',
);
echo $k['name']($k['value']);
?>"  method="POST" class="cf" onsubmit="return search(this)">
				<input type="text" value="<?php echo $this->_var['keywords']; ?>" name="keywords" class="f-input" />					
				<input type="submit" value="搜索" name="submit" class="formbutton" />
			 </form>
			<div class="clear"></div>	
		</div>		
	</div>
	<?php endif; ?> 
	
	<div id="sysmsg-error-box">
		<div id="sysmsg-error" class="sysmsgw <?php if ($this->_var['error'] == ''): ?>hidd<?php endif; ?>">
			<div class="sysmsg"><span><?php echo $this->_var['error']; ?></span><span class="close"><?php echo $this->_var['lang']['XY_CLOSE']; ?></span></div>
		</div>
		<div id="sysmsg-success" class="sysmsgw <?php if ($this->_var['success'] == ''): ?>hidd<?php endif; ?>">
			<div class="sysmsg"><span><?php echo $this->_var['success']; ?></span><span class="close"><?php echo $this->_var['lang']['XY_CLOSE']; ?></span></div>
		</div>
	</div>
	
	