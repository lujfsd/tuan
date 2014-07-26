<p class=nav><a href="<?php 
$k = array (
  'name' => 'a_u',
  'value' => 'Message/index',
);
echo $k['name']($k['value']);
?>" target=_blank><?php 
$k = array (
  'name' => 'a_L',
  'value' => 'XY_SEE_ALL',
);
echo $k['name']($k['value']);
?>(<span id="new-message-count"><?php echo $this->_var['message_count']; ?></span>)</a> | <a href="<?php 
$k = array (
  'name' => 'a_u',
  'value' => 'Message/index',
);
echo $k['name']($k['value']);
?>#consult-form-head" target=_blank><?php 
$k = array (
  'name' => 'a_L',
  'value' => 'XY_I_SENT_MSG',
);
echo $k['name']($k['value']);
?></a></p>
<ul class="list" id="new-message-ul">
<?php $_from = $this->_var['message_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'message_item');if (count($_from)):
    foreach ($_from AS $this->_var['message_item']):
?>
<li><a href="<?php 
$k = array (
  'name' => 'a_u',
  'value' => 'Message/index',
);
echo $k['name']($k['value']);
?>#consult-entry-<?php echo $this->_var['message_item']['id']; ?>"><?php echo $this->_var['message_item']['content']; ?></a></li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>