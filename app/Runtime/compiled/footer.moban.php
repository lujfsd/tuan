<div align="center"><?php 
$k = array (
  'name' => 'advLayout',
  'id' => '底部通栏广告',
);
echo $this->_hash . $k['name'] . '|' . serialize($k) . $this->_hash;
?></div>
	<div id="ftw">
        <div id="ft">
			<p class="contact"><a href="<?php 
$k = array (
  'name' => 'a_u',
  'value' => 'Message/feedback',
);
echo $k['name']($k['value']);
?>"><?php echo $this->_var['lang']['FEEDBACK']; ?></a></p>
            <ul class="cf">
			
				<?php $_from = $this->_var['help_center']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'help_cate');$this->_foreach['help_cate'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['help_cate']['total'] > 0):
    foreach ($_from AS $this->_var['help_cate']):
        $this->_foreach['help_cate']['iteration']++;
?>
				<li class="col">
                    <h3><?php echo $this->_var['help_cate']['name_1']; ?></h3>
                    <ul class="sub-list">
						<?php $_from = $this->_var['help_cate']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_item');if (count($_from)):
    foreach ($_from AS $this->_var['article_item']):
?>
                        <li><a href="<?php echo $this->_var['article_item']['url']; ?>" target="<?php echo $this->_var['article_item']['target']; ?>"><?php echo $this->_var['article_item']['name_1']; ?></a>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						<?php if (($this->_foreach['help_cate']['iteration'] - 1) == 1): ?>
						<li><a href="<?php echo a_u("Rss/index","cityname-".$this->_var['currentCity']['py'])?>" target="_blank"><?php echo $this->_var['lang']['RSS_SUBSCRIBE']; ?></a></li>
						<?php endif; ?>
                    </ul>
                </li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <li class="col end">
                    <div class=logo-footer><a href="<?php echo $this->_var['__ROOT__']; ?>/"><img alt="" 
  src="<?php echo $this->_var['CND_URL']; ?><?php 
$k = array (
  'name' => 'a_fanweC',
  'value' => 'FOOT_LOGO',
);
echo $k['name']($k['value']);
?>"></a></div>
                </li>
            </ul>
	    <?php if ($this->_var['links']): ?>
		<div align="left" style="padding:2px;">
		<?php $_from = $this->_var['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'al');if (count($_from)):
    foreach ($_from AS $this->_var['al']):
?>
		<?php if ($this->_var['al']['type'] == 1): ?>
		<a href="<?php echo $this->_var['al']['url']; ?>" target="blank" title="<?php echo $this->_var['al']['name']; ?>"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['al']['logo']; ?>" style="border:1px solid #d1d1d1" width="88" height="31"></a>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		<div class="clear"></div>
		<?php $_from = $this->_var['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'al');if (count($_from)):
    foreach ($_from AS $this->_var['al']):
?>
		<?php if ($this->_var['al']['type'] == 0): ?>
		<a href="<?php echo $this->_var['al']['url']; ?>" target="blank"  title="<?php echo $this->_var['al']['name']; ?>"><?php echo $this->_var['al']['name']; ?></a>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</div>
<div class="blank"></div>
	   <?php endif; ?>
            <div class=copyright>
				<div>

				<?php $_from = $this->_var['foot_navs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['nav']):
?>
					<?php if ($this->_var['key'] != 0): ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php endif; ?>
					<a href="<?php echo $this->_var['nav']['url']; ?>" <?php if ($this->_var['nav']['act'] == 1): ?>class="act"<?php endif; ?>  target="<?php echo $this->_var['nav']['target']; ?>" ><?php echo $this->_var['nav']['name_1']; ?></a>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</div>
                <p><?php echo $this->_var['CFG']['PAGE_BOTTOM']; ?><?php echo $this->_var['CFG']['STATS_CODE']; ?></p>
            </div>
        </div>
    </div>
</div>
<?php if ($this->_var['CFG']['SMS_SUBSCRIBE'] == 1 && $_REQUEST['m'] != 'Suppliers'): ?>
<div class="smssubscribe-dialog-box">
	<div class="shadow">&nbsp;</div>
	<dl>
		<dt><a href="javascript:;" class="close"><?php echo $this->_var['lang']['CLOSE']; ?></a></dt>
		<dd>
			<div id="smssubscribe-1" class="ss-form">
				<form name="ss-from1" id="ss-from1" method="post" action="<?php echo $this->_var['__ROOT__']; ?>/index.php">
				<h3>
					<?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['XY_SMS_SUBSCRIBE_TITLE'],
  'b' => $this->_var['currentCity']['name'],
);
echo $k['name']($k['a'],$k['b']);
?>
				</h3>
				<div class="ss-item">
					<span><?php echo $this->_var['lang']['XY_MOBILE']; ?></span><input id="sms-subscribe-mobile" name="mobile_phone" type="text" class="txt" />
				</div>
				<div class="ss-hit"><?php echo $this->_var['lang']['XY_MOBILE_HIT']; ?></div>
				<div class="ss-item">
					<span><?php echo $this->_var['lang']['XY_VERIFY']; ?></span><input id="sms-subscribe-verify" name="verify" type="text" class="txt code" /><img src="" id="sms-subscribe-verify-img" title="<?php echo $this->_var['lang']['XY_VERIFY_RESET']; ?>" onclick="this.src='index.php?m=Ajax&a=verify&rand='+ Math.random();"/>
				</div>
				<div class="ss-btns">
					<input id="ss1-submit" type="image" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/ss-1.jpg" />
				</div>
				</form>
			</div>
			<div id="smssubscribe-2" class="ss-form">
				<form name="ss-from2" id="ss-from2" method="post" action="<?php echo $this->_var['__ROOT__']; ?>/index.php">
				<h3>
					<?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['XY_SMS_SUBSCRIBE_TITLE'],
  'b' => $this->_var['currentCity']['name'],
);
echo $k['name']($k['a'],$k['b']);
?>
				</h3>
				<div class="ss-item">
					<?php echo $this->_var['lang']['XY_SMS_SUBSCRIBE_CODE_SEND']; ?>：<strong class="mobile"></strong>
				</div>
				<div class="ss-item">
					<span><?php echo $this->_var['lang']['XY_SMS_SUBSCRIBE_CODE']; ?></span><input id="sms-subscribe-code" name="code" type="text" class="txt" />
				</div>
				<div class="ss-hit"><?php echo $this->_var['lang']['XY_SMS_SUBSCRIBE_CODE_HIT']; ?></div>
				<div class="ss-btns">
					<input id="ss2-submit" type="image" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/ss-2.jpg" />
				</div>
				</form>
			</div>
			<div id="smssubscribe-3" class="ss-form hidd">
				<div class="ss-succ">
					<strong><?php echo $this->_var['lang']['XY_SMS_SUBSCRIBE_SUCC']; ?></strong>
					<p>
					<?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['XY_SMS_SUBSCRIBE_SUCC_NOTICE'],
  'b' => $this->_var['currentCity']['name'],
);
echo $k['name']($k['a'],$k['b']);
?>
					</p>
				</div>
			</div>
		</dd>
	</dl>
</div>
<div class="unsmssubscribe-dialog-box">
	<div class="shadow">&nbsp;</div>
	<dl>
		<dt><a href="javascript:;" class="close"><?php echo $this->_var['lang']['CLOSE']; ?></a></dt>
		<dd>
			<div id="unsmssubscribe-1" class="ss-form">
				<form name="unss1-from1" id="unss1-from1" method="post" action="<?php echo $this->_var['__ROOT__']; ?>/index.php">
				<h3>
				<?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['XY_SMS_UNSUBSCRIBE_TITLE'],
  'b' => $this->_var['currentCity']['name'],
);
echo $k['name']($k['a'],$k['b']);
?>
				</h3>
				<div class="ss-item">
					<span><?php echo $this->_var['lang']['XY_MOBILE']; ?></span><input id="unsms-subscribe-mobile" name="mobile_phone" type="text" class="txt" />
				</div>
				<div class="ss-hit"><?php echo $this->_var['lang']['XY_MOBILE_HIT']; ?></div>
				<div class="ss-item">
					<span><?php echo $this->_var['lang']['XY_VERIFY']; ?></span><input id="unsms-subscribe-verify" name="verify" type="text" class="txt code" /><img src="" id="unsms-subscribe-verify-img" title="<?php echo $this->_var['lang']['XY_VERIFY_RESET']; ?>" onclick="this.src='index.php?m=Ajax&a=verify&rand='+ Math.random();"/>
				</div>
				<div class="ss-btns">
					<input id="unss1-submit" type="image" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/ss-3.jpg" />
				</div>
				</form>
			</div>
			<div id="unsmssubscribe-2" class="ss-form">
				<form name="unss-from2" id="unss-from2" method="post" action="<?php echo $this->_var['__ROOT__']; ?>/index.php">
				<h3>
				<?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['XY_SMS_UNSUBSCRIBE_TITLE'],
  'b' => $this->_var['currentCity']['name'],
);
echo $k['name']($k['a'],$k['b']);
?>
				</h3>
				<div class="ss-item">
					<?php echo $this->_var['lang']['XY_SMS_UNSUBSCRIBE_CODE_SEND']; ?>：<strong class="mobile"></strong>
				</div>
				<div class="ss-item">
					<span><?php echo $this->_var['lang']['XY_SMS_UNSUBSCRIBE_CODE']; ?></span><input id="unsms-subscribe-code" name="code" type="text" class="txt" />
				</div>
				<div class="ss-hit"><?php echo $this->_var['lang']['XY_SMS_UNSUBSCRIBE_CODE_HIT']; ?></div>
				<div class="ss-btns">
					<input id="unss2-submit" type="image" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/ss-2.jpg" />
				</div>
				</form>
			</div>
			<div id="unsmssubscribe-3" class="ss-form hidd">
				<div class="ss-succ">
					<strong><?php echo $this->_var['lang']['XY_SMS_UNSUBSCRIBE_SUCC']; ?></strong>
					<p>
					<?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['XY_SMS_UNSUBSCRIBE_SUCC_NOTICE'],
  'b' => $this->_var['currentCity']['name'],
);
echo $k['name']($k['a'],$k['b']);
?>
					</p>
				</div>
			</div>
		</dd>
	</dl>
</div>
<?php endif; ?>
<!--
<div class="saler_map_window">
<div class="op_bar">
<?php echo $this->_var['lang']['VIEW_BIG_MAP']; ?> <a href="javascript:void(0);" class="close"><?php echo $this->_var['lang']['CLOSE']; ?> </a>
</div>
<iframe scrolling="no" frameborder="0" src="" style="height:480px; width:730px;"></iframe>
</div>
-->
</body>
<script type="text/javascript" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/js/ajaxSend.js"></script>
</html>