<div id="contentW">
				<div id="deal-share">
					<div class="deal-share-top">
						<div class="deal-share-links">
							<h4><?php echo $this->_var['lang']['XY_SHARE_TO']; ?></h4>
							<ul class="cf">
								<?php echo $this->fetch('Inc/others/referrals_url.moban'); ?>
							</ul>
						</div>
					</div>
					<div class="deal-share-fix"></div>
					
					<div id="deal-share-im-c" style="display:none; clear:both; width:483px; margin-left:24px;">
						<div class="deal-share-im-b">
							<h3><?php echo $this->_var['lang']['XY_SHARE_TO_FREAND']; ?></h3>
							<p>
								<input id="share-copy-text-<?php echo $this->_var['goods']['id']; ?>" class="f-input" value="<?php echo $this->_var['goods']['ref_urllink']; ?>" size="30">
								<input onclick="copy_text(<?php echo $this->_var['goods']['id']; ?>);" class="formbutton" value="<?php echo $this->_var['lang']['XY_COPY']; ?>" type="button">
							</p>
						</div>
					</div>
				</div>
				<div class="deal-intro-top"></div>
				<div id="deal-intro" class="cf">
					<h1>
						<?php if ($this->_var['goods']['promote_begin_time'] > $this->_var['TIME']): ?>
						<span><?php echo $this->_var['cate_info']['name_1']; ?><?php echo $this->_var['lang']['XY_GROUP_FORECASE']; ?></span>
						<?php elseif (! $this->_var['goods']['is_end']): ?>
						<?php if ($this->_var['goods']['score_goods'] == 0): ?>
							<?php if ($this->_var['goods']['type_id'] == 2): ?>
							<span><?php echo $this->_var['cate_info']['name_1']; ?><?php echo $this->_var['lang']['XY_BELOW_GROUP']; ?></span>
							<?php else: ?>
							<span><?php echo $this->_var['cate_info']['name_1']; ?><?php echo $this->_var['lang']['XY_TODAY_GROUP']; ?>：</span>
							<?php endif; ?>
						<?php elseif ($this->_var['goods']['score_goods'] == 1): ?>
						<span><?php echo $this->_var['cate_info']['name_1']; ?><?php echo $this->_var['lang']['SCORE_GOODS']; ?>：</span>
						<?php endif; ?>
						<?php endif; ?><?php echo $this->_var['goods']['name_1']; ?>
					</h1>
					<div class="main">
						<div class="deal-buy">
							<div class="deal-price-tag"></div>
							<p class="deal-price" id="deal-price">
								<?php echo $this->fetch('Inc/common/goods_btn_info.moban'); ?>
							</p>
						</div>
						<table class="deal-discount">
							<tbody>
								<tr>
									<th><?php echo $this->_var['lang']['XY_MAKET_PRICE']; ?></th>
									<th><?php echo $this->_var['lang']['XY_SHOP_DISCOUNT']; ?></th>
									<th><?php echo $this->_var['lang']['XY_SHOP_SAVE']; ?></th>
								</tr>
								<tr>
									<td><?php echo $this->_var['goods']['market_price_format']; ?></td>
									<td><?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['XY_SHOP_SAVE_POINT'],
  'b' => $this->_var['goods']['discountfb'],
);
echo $k['name']($k['a'],$k['b']);
?></td>
									<td><?php echo $this->_var['goods']['save']; ?></td>
								</tr>
							</tbody>
						</table>
						<?php echo $this->fetch('Inc/common/goods_date_info.moban'); ?>				
						<div id=deal-status class="deal-box deal-status deal-status-open">
						<?php echo $this->fetch('Inc/common/goods_status_info.moban'); ?>
						</div>
					</div>
					<div class=side>
						<div class="deal-buy-cover-img" id="goods_imgs">
							<div class="mid">
								<ul>
								<?php $_from = $this->_var['goods']['gallery']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'gallery_item');$this->_foreach['gallery_item'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['gallery_item']['total'] > 0):
    foreach ($_from AS $this->_var['gallery_item']):
        $this->_foreach['gallery_item']['iteration']++;
?>
									<li <?php if (($this->_foreach['gallery_item']['iteration'] <= 1)): ?>class="first"<?php endif; ?>><img src="<?php echo $this->_var['CND_URL']; ?><?php echo $this->_var['gallery_item']['big_img']; ?>"></li>
								<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</ul>
								<div id="img_list">
									<?php if (count($GLOBALS['tpl']->_var['goods']['gallery']) > 1) {?>
									<?php $_from = $this->_var['goods']['gallery']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'gallery_item');$this->_foreach['gallery_item'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['gallery_item']['total'] > 0):
    foreach ($_from AS $this->_var['gallery_item']):
        $this->_foreach['gallery_item']['iteration']++;
?>
									<a ref="<?php echo $this->_foreach['gallery_item']['iteration']; ?>" <?php if (($this->_foreach['gallery_item']['iteration'] <= 1)): ?>class="active"<?php endif; ?>><?php echo $this->_foreach['gallery_item']['iteration']; ?></a>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									<?php } ?>
								</div>
							</div>
						</div>						
						<div class=digest>
							<br/><?php echo $this->_var['goods']['brief']; ?>
						</div>
					</div>
				</div>
				<div class="deal-intro-bottom"></div>
				<div id=deal-stuff class=cf>
					<div class="box box-split">
						<div class=box-top></div>
						<div class="box-content cf">
							<div class=main>
								<H2><?php echo $this->_var['lang']['XY_GROUP_DESC']; ?></H2>
								<div class="blk detail">
									<?php echo $this->_var['goods']['goods_desc_1']; ?>
								</div>
								<?php if ($this->_var['goods']['suppliers']['desc']): ?>
								<H2><?php echo $this->_var['lang']['XY_COMMER_INTRO']; ?></H2>
								<div class="blk detail">
									<?php echo $this->_var['goods']['suppliers']['desc']; ?>
								</div>
								<?php endif; ?>
								<?php if ($this->_var['goods']['goods_reviews'] || $this->_var['goods']['reviews_list']): ?>
								<H2><?php echo $this->_var['lang']['XY_THEY_SAY']; ?></H2>
								<div class="blk review">
									<?php if ($this->_var['goods']['goods_reviews']): ?>
									<p><?php echo $this->_var['goods']['goods_reviews']; ?></p>
									<?php endif; ?>
									<ul class=review>
										<?php $_from = $this->_var['goods']['reviews_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'reviews');if (count($_from)):
    foreach ($_from AS $this->_var['reviews']):
?>
										<li><?php echo $this->_var['reviews']['content']; ?>
										<span>
											——<a href="<?php echo $this->_var['reviews']['url']; ?>" target="_blank"><?php echo $this->_var['reviews']['user_name']; ?></a>
											<?php if ($this->_var['reviews']['webname']): ?>
											（<?php echo $this->_var['reviews']['webname']; ?>）
											<?php endif; ?>
										</span>
										</li>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									</ul>
								</div>
								<?php endif; ?>
								<?php if ($this->_var['goods']['web_reviews']): ?>
								<H2><?php echo $this->_var['lang']['XY_OWN_SAY']; ?></H2>
								<div class="blk voice">
									<?php echo $this->_var['goods']['web_reviews']; ?>
								</div>
								<?php endif; ?>
                                                                <H2><?php echo $this->_var['goods']['suppliers']['name']; ?></H2>
									<ul>									
										<?php if ($this->_var['goods']['suppliers']['web']): ?>
										<li><a href="<?php echo $this->_var['goods']['suppliers']['web']; ?>" target="_blank"><?php echo $this->_var['goods']['suppliers']['web']; ?></a>‎</li>
										<?php endif; ?>									
										<?php if ($this->_var['goods']['suppliers']['brief']): ?>
										<li><?php echo $this->_var['lang']['XY_SURPLUS_BRIEF']; ?><?php echo $this->_var['goods']['suppliers']['brief']; ?>‎</li>
										<?php endif; ?>
										<?php if ($this->_var['goods']['map_img']): ?>
										<li>
										<span class="saler_map"><img src="<?php echo $this->_var['goods']['map_img']; ?>" />
										<a href='javascript:void(0);' id="show_map"><?php echo $this->_var['lang']['VIEW_BIG_MAP']; ?></a>
										<span id="saler_id"><?php echo $this->_var['goods']['suppliers']['id']; ?></span>
										</span>
										</li>
										<?php endif; ?>
										<?php $_from = $this->_var['goods']['suppliers_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'depart');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['depart']):
?>
										<?php if ($this->_var['key'] == 0): ?>
											<?php if ($this->_var['depart']['address']): ?>
											<li><?php echo $this->_var['depart']['address']; ?>
											<?php if ($this->_var['depart']['map']): ?>
											（<a href="<?php echo $this->_var['depart']['map']; ?>" target="_blank"><?php echo $this->_var['lang']['XY_VIEW_MAP']; ?></a>）
											<?php endif; ?>
											</li>
											<?php endif; ?>									
											<?php if ($this->_var['depart']['tel']): ?>
											<li><?php echo $this->_var['depart']['tel']; ?>‎</li>
											<?php endif; ?>
											<?php if ($this->_var['depart']['operating']): ?>
											<li><?php echo $this->_var['lang']['XY_SURPLUS_OPERTATIONG']; ?><?php echo $this->_var['depart']['operating']; ?>‎</li>
											<?php endif; ?>
											<?php if ($this->_var['depart']['bus']): ?>
											<li><?php echo $this->_var['lang']['XY_SURPLUS_BUS']; ?><?php echo $this->_var['depart']['bus']; ?>‎</li>
											<?php endif; ?>
											<li>‎</li>
										<?php endif; ?>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
										
										<?php if ($this->_var['goods']['suppliers']['img']): ?>
										<li style="text-align:center">
											<br/><img src="<?php echo $this->_var['CND_URL']; ?><?php echo $this->_var['goods']['suppliers']['img']; ?>" width="200">
										</li>
										<?php endif; ?>
										<li style="text-align:center">
											<a href="<?php echo a_u("Supplier/show","id-".$GLOBALS['tpl']->_var['goods']['suppliers']['id']); ?>"><?php echo $this->_var['lang']['VIEW_SUPPLIER_INFO']; ?></a>
										</li>
									</ul>
							</div>
						
							<div class=clear></div>
						</div>
						<div class=box-bottom></div>
					</div>
				</div>
			</div>
			
			<script type="text/javascript">
				var endTime = <?php echo $this->_var['goods']['promote_end_time']; ?>;
				var beginTime = <?php echo $this->_var['goods']['promote_begin_time']; ?>;
				var system_time = <?php echo $this->_var['TIME']; ?>;
				var sysSecond;
				var interValObj;
				var statusTimeout;
				
				
				function setRemainTime()
				{
					if (sysSecond > 0)
					{
						var second = Math.floor(sysSecond % 60);              // 计算秒     
						var minite = Math.floor((sysSecond / 60) % 60);       //计算分
						var hour = Math.floor((sysSecond / 3600) % 24);       //计算小时
						var day = Math.floor((sysSecond / 3600) / 24);        //计算天
						var timeHtml = hour+LANG.JS_HOUR+minite+LANG.JS_MINUTE;
						if(day > 0)
							timeHtml =day+LANG.JS_DAY+"</li>" + timeHtml;
						timeHtml+=second+LANG.JS_SECOND;
						
						try
						{
							$("#counter").html(timeHtml);
							sysSecond--;
						}
						catch(e){}
					}
					else
					{
						window.clearTimeout(interValObj);
						window.location.href=window.location.href;
					}
					interValObj = window.setTimeout("setRemainTime()", 1000); 	
				}
				
				<?php if ($this->_var['goods']['is_end'] != 1 && $this->_var['goods']['promote_begin_time'] <= $this->_var['TIME'] && $this->_var['goods']['is_group_fail'] != 1): ?>
					sysSecond = endTime - system_time;
					setRemainTime();
				<?php elseif ($this->_var['goods']['is_end'] != 1 && $this->_var['goods']['promote_begin_time'] > $this->_var['TIME'] && $this->_var['goods']['is_group_fail'] != 1): ?>
					var GOODS_BUY_URL = "";
					sysSecond = beginTime - system_time;
					setRemainTime();
				<?php endif; ?>
			</script>