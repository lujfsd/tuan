<?php if (!defined('THINK_PATH')) exit();?><select name="quan_id" class="bLeft">
	<option value="0">选择商圈</option>
	<?php if(is_array($quan_list)): foreach($quan_list as $key=>$quan_item): ?><option value="<?php echo ($quan_item["id"]); ?>" <?php if($quan_item['selected']): ?>selected="selected"<?php endif; ?>><?php echo ($quan_item["name"]); ?></option><?php endforeach; endif; ?>
</select>