<?php echo $this->fetch('Inc/header.moban'); ?>
<script type="text/javascript">
var VAR_MODULE = 'm';
var VAR_ACTION = 'a';
var NO_SELECT = '<?php echo $this->_var['lang']['NO_SELECT']; ?>';
var CONFIRM_DELETE = '<?php echo $this->_var['lang']['CONFIRM_DELETE']; ?>';
var goodsType = '<?php echo $this->_var['goods_type']; ?>';
var maxMoney = '<?php echo $this->_var['user_info_money']; ?>';
var totalPrice ='<?php echo $this->_var['cart_total_price']; ?>';
var isInquiry  = 0;
var payType  = <?php 
$k = array (
  'name' => 'a_fanweC',
  'value' => 'PAY_SHOW_TYPE',
);
echo $k['name']($k['value']);
?>;
var isOrder = false;
var is_smzq = 0;
</script>
<div id="bdw" class="bdw">	
	<?php echo $this->fetch('Inc/cart/cart_check_list.moban'); ?>
</div>
<?php echo $this->fetch('Inc/footer.moban'); ?>

<script type="text/javascript">
jQuery(function($){
	countCartTotal();
});
</script>