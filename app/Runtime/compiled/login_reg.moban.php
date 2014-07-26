<?php echo $this->fetch('Inc/header.moban'); ?>
<div id="bdw" class="bdw">
	<div id="bd" class="cf">
		<div id="content">
			<div id="signup" style="position:relative;">
				<div id="deal-buy-login">
					<h3><?php echo $this->_var['lang']['XY_HAVA_ACCOUNT']; ?></h3>
					<form action="<?php 
$k = array (
  'name' => 'a_u',
  'value' => 'User/doLogin',
);
echo $k['name']($k['value']);
?>" method="post" id="deal-buy-form-login">
						<div id="deal-buy-login-form">
							<p><span><?php echo $this->_var['lang']['LOGIN_EMAIL']; ?></span><input tabindex="1" type="text" size="30" name="email" id="login-email-address" class="f-input" value="<?php echo $this->_var['user']['email']; ?>"/>
							</p>
							<p>
								<span><?php echo $this->_var['lang']['XY_PASS']; ?></span><input type="password" tabindex="2"  class="f-input" name="user_pwd" size="30" id="login-password"/>
							</p>
							<?php if (a_fanweC ( 'REGISTER_VERIFY' ) == 1): ?>
							<p class="cf">
								<span style="float:left;padding-top:3px;"><?php echo $this->_var['lang']['XY_VERIFY']; ?></span><input type="text" size="30" name="login_verify" id="login_verify" class="f-input" style="width:100px;float:left;margin-right:9px;" /><img id="login-verify-img" style="float:left;cursor:pointer;height:auto;align="absmiddle" title="<?php echo $this->_var['lang']['XY_VERIFY_RESET']; ?>" onclick="this.src='<?php echo $this->_var['HTTP_URL']; ?>/index.php?m=Ajax&a=verify&rand='+ Math.random();" src='<?php echo $this->_var['HTTP_URL']; ?>/index.php?m=Ajax&a=verify&rand='+ Math.random();/>
							</p>
							<?php endif; ?>
							<p class="act">
								<input type="hidden" name="redirect" value="<?php echo $this->_var['redirect']; ?>" />
								<input type="hidden" name="goods_id" value="<?php echo $this->_var['goods_id']; ?>" />
								<input type="hidden" name="number" value="<?php echo $this->_var['number']; ?>" />
								<input type="hidden" name="is_cart" value="1" />
								<input type="submit" value="<?php echo $this->_var['lang']['XY_USER_LOGIN']; ?>" name="commit" id="login-submit" class="formbutton"/>
								
							</p>
							<?php if (( a_fanweC ( 'SINA_KEY' ) != '' ) || ( a_fanweC ( 'QQ_KEY' ) != '' ) || ( a_fanweC ( '360_KEY' ) != '' ) || ( a_fanweC ( 'ALIAPY_PARTNER' ) != '' ) || ( a_fanweC ( '800_KEY' ) != '' ) || ( a_fanweC ( 'TXQQ_KEY' ) != '' ) || ( a_fanweC ( '2345_KEY' ) != '' ) || ( a_fanweC ( 'APP_KEY_baidu' ) != '' )): ?>
                            <p>
                            		   <?php if (a_fanweC ( 'SINA_KEY' ) != ''): ?>
	                       					<a href="<?php echo $this->_var['__ROOT__']; ?>/index.php?m=user&a=login_sina&oauth_sina=1" target="_blank"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/sina.png" /></a>
									   <?php endif; ?>	
											<?php if (a_fanweC ( 'QQ_KEY' ) != ''): ?>
			                                 	<a href="<?php echo $this->_var['__ROOT__']; ?>/index.php?m=user&a=login_qq&oauth_qq=1" target="_blank"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/qq.png" /></a>
											<?php endif; ?>
	                                   <?php if (a_fanweC ( '360_KEY' ) != ''): ?>
	                                       <a href="<?php echo $this->_var['__ROOT__']; ?>/index.php?m=user&a=login_360&oauth_360=1" target="_blank"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/360.gif" /></a>
									   <?php endif; ?>
		                                    <?php if (a_fanweC ( 'ALIAPY_PARTNER' ) != ''): ?>
		                                       <a href="<?php echo $this->_var['__ROOT__']; ?>/index.php?m=user&a=login_alipay&oauth_alipay=1" target="_blank"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/act_zfb.gif" /></a>
											<?php endif; ?>
	                                   <?php if (a_fanweC ( '800_KEY' ) != ''): ?>
					        		   		<a href="<?php echo $this->_var['__ROOT__']; ?>/index.php?m=user&a=login_800&oauth_800=1" target="_blank"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/800.png" /></a>									
									   <?php endif; ?>
											
									   <?php if (a_fanweC ( '2345_KEY' ) != ''): ?>
	                                 		<a href="<?php echo $this->_var['__ROOT__']; ?>/index.php?m=user&a=login_2345&oauth_2345=1" target="_blank"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/login_2345.gif" /></a>
									   <?php endif; ?>
											<?php if (a_fanweC ( 'APP_KEY_baidu' ) != ''): ?>
			                                	 <a href="<?php echo $this->_var['__ROOT__']; ?>/index.php?m=user&a=login_baidu&oauth_baidu=1" target="_blank"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/login-baidu.gif" /></a>
											<?php endif; ?>
									   <?php if (a_fanweC ( 'TXQQ_KEY' ) != ''): ?>
			                                 	<a href="<?php echo $this->_var['__ROOT__']; ?>/index.php?m=user&a=login_txqq&oauth_txqq=1" target="_blank"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/txqq.png" /></a>
									   <?php endif; ?>                                    
							</p>
						   <?php endif; ?>
						</div>
					</form>
				</div>
			</div>
			<div class="box" id="deal-buy-box">
				<div class="box-top"></div>
				<div class="box-content">
					<div class="head">
						<h2><?php echo $this->_var['lang']['XY_QUICK_REG_LOGIN']; ?></h2>
					</div>
					<?php echo $this->fetch('Inc/user/reg_info.moban'); ?>
				</div>
				<div class="box-bottom"></div>
			</div>
		</div>
		<div id="sidebar"> </div>
	</div>
	<!-- bd end -->
</div>
<?php echo $this->fetch('Inc/footer.moban'); ?>
