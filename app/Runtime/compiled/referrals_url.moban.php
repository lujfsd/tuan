<?php if ($this->_var['is_referrals_page'] != 1): ?>
<script type="text/javascript">
	function share_url(id)
	{
		if ($("#deal-share-im-c").css("display") == "none") {			
			$("#share-copy-text").val($("#share_url_"+id).val());
			$("#deal-share-im-c").show();
		}
		else 
			$("#deal-share-im-c").hide();
	}
</script>
<input type="hidden" value="<?php echo $this->_var['goods']['ref_urllink']; ?>" id="share_url_<?php echo $this->_var['goods']['id']; ?>" />
<li><a id="shart_im_<?php echo $this->_var['goods']['id']; ?>" class="im" href="javascript:share_url(<?php echo $this->_var['goods']['id']; ?>);" >MSN/QQ</a></li>
<?php endif; ?>
								<li><a class="kaixin" 
href="http://www.kaixin001.com/repaste/share.php?rurl=<?php echo $this->_var['goods']['ref_urllink']; ?>&rcontent=<?php echo $this->_var['goods']['urlbrief']; ?>&rtitle=<?php echo $this->_var['goods']['urlname']; ?>" 
target=_blank>开心</a></li>
								<li><a class="renren" 
href="http://share.renren.com/share/buttonshare.do?link=<?php echo $this->_var['goods']['ref_urllink']; ?>&title=<?php echo $this->_var['goods']['urlname']; ?>" 
target=_blank>人人</a></li>
								<li><a class="douban" 
href="http://www.douban.com/recommend/?url=<?php echo $this->_var['goods']['ref_urllink']; ?>&title=<?php echo $this->_var['goods']['urlname']; ?>&comment=<?php echo $this->_var['goods']['urlbrief']; ?>" 
target=_blank>豆瓣</a></li>
								<li><a class="sina" 
href="http://v.t.sina.com.cn/share/share.php?url=<?php echo $this->_var['goods']['ref_urllink']; ?>&title=<?php echo $this->_var['goods']['urlname']; ?>" 
target=_blank>新浪微博</a></li>
								<li><a id="deal-buy-mailto" class="email" 
href="mailto:?subject=<?php echo $this->_var['goods']['urlgbname']; ?>&body=<?php echo $this->_var['goods']['urlgbbody']; ?><?php echo $this->_var['goods']['ref_urllink']; ?>">邮件</a></li>