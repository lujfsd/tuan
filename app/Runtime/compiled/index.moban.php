<?php echo $this->fetch('Inc/header.moban'); ?>
<?php 
if(($_REQUEST ['m'] == 'Goods' || $_REQUEST ['m'] == 'Index') && ($_REQUEST ['a'] == 'index' || $_REQUEST ['a'] == 'showcate')){
	$index_type = $_REQUEST['index_type'];
	$GLOBALS['tpl']->assign('index_type',$index_type);

	function getUrl($type,$query_arr){
	    $url = '?';
	    foreach($query_arr as $key=>$value){
	        if($value[0] != $type){
	            $url .=$value[0].'='.$value[1].'&';
	        }
	    }
	    return $url.$type.'=';
	}
}
?>
<div id="bdw" class="bdw">
	<div id="bd" class="cf">
		<div id="deal-default">
				<?php if ($this->_var['goods_list'] || ( $_REQUEST['m'] == 'Goods' && $_REQUEST['a'] == 'showcate' )): ?>
                                    <?php if ($_REQUEST['index_type'] == old && $this->_var['goods_list']): ?>
                                        <?php echo $this->fetch('Inc/goods/goods_listx.moban'); ?>
                                    <?php else: ?>
                                        <?php if ($this->_var['goods_list']): ?>
                                            <?php echo $this->fetch('Inc/goods/goods_list.moban'); ?>
                                         <?php else: ?>
                                            <?php echo $this->fetch('Inc/goods/goods_subscribe.moban'); ?>
                                         <?php endif; ?>
                                    <?php endif; ?>
				<?php elseif ($this->_var['goods']): ?>
				<?php echo $this->fetch('Inc/goods/goods_info.moban'); ?>
				<?php else: ?>
				<?php echo $this->fetch('Inc/goods/goods_subscribe.moban'); ?>
				<?php endif; ?>
			<div id="sidebar" style="padding-top:30px;">
				<?php if ($this->_var['goods'] && ! $this->_var['goods_list'] || $_REQUEST['index_type'] == old): ?>
                                    <?php echo $this->fetch('Inc/right.moban'); ?>
                                    <?php 
$k = array (
  'name' => 'advLayout',
  'id' => '首页右侧广告位',
);
echo $this->_hash . $k['name'] . '|' . serialize($k) . $this->_hash;
?>
                                <?php else: ?>
                                     <?php 
$k = array (
  'name' => 'advLayout',
  'id' => '右侧无团购时的广告位',
);
echo $this->_hash . $k['name'] . '|' . serialize($k) . $this->_hash;
?>
                                <?php endif; ?>
			</div>
		</div>
                
	</div>
	<!-- bd end -->
</div>

<?php echo $this->fetch('Inc/footer.moban'); ?>