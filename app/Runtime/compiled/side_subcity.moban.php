<?php if ($this->_var['sub_citys']): ?>
<div class="side-sub-citys">
	<ul>
		<?php $_from = $this->_var['sub_citys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'scity');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['scity']):
?>
		<li <?php if ($this->_var['scity']['id'] == $this->_var['currentCity']['id']): ?>class="current"<?php endif; ?> <?php if ($this->_var['k'] >= 5): ?>style="display: none;"<?php endif; ?>><a href="<?php echo $this->_var['scity']['url']; ?>" rel="<?php echo $this->_var['scity']['pid']; ?>"><?php echo $this->_var['scity']['name']; ?></a></li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		<?php if( count( $this->_var['sub_citys'] ) > 5 ) {?>
		<li ><a href="javascript:void(0);" onclick="$('.side-sub-citys li').show(); this.parentNode.parentNode.removeChild( this.parentNode ); return false;">更多城市</a></li>
		<?php }?>
	</ul>
</div>
<?php endif; ?>