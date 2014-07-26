<ul id="account">
<?php if ($_SESSION['user_id'] != 0): ?>
<li class="username" title="<?php echo $_SESSION['user_name']; ?>">
<?php echo $this->_var['lang']['XY_WELCOME']; ?>
<?php 
$k = array (
  'name' => 'a_msubstr',
  'value' => $_SESSION['user_name'],
  'a1' => '0',
  'a2' => '4',
);
echo $k['name']($k['value'],$k['a1'],$k['a2']);
?>！</li>
<li class="account"><a href="<?php 
$k = array (
  'name' => 'a_u',
  'a1' => 'UcModify/index',
);
echo $k['name']($k['a1']);
?>" id="myaccount" class="account"><?php echo $this->_var['lang']['XY_USER_CENTER']; ?></a></li>
<li class="logout"><a href="<?php 
$k = array (
  'name' => 'a_u',
  'a1' => 'User/logout',
);
echo $k['name']($k['a1']);
?>"><?php echo $this->_var['lang']['XY_USER_LOGOUT']; ?></a></li>
<?php else: ?>
<li class=login><a href="<?php 
$k = array (
  'name' => 'a_u',
  'a1' => 'User/login',
);
echo $k['name']($k['a1']);
?>"><?php echo $this->_var['lang']['XY_USER_LOGIN']; ?></a></li>
<li class=signup><a href="<?php 
$k = array (
  'name' => 'a_u',
  'a1' => 'User/register',
);
echo $k['name']($k['a1']);
?>"><?php echo $this->_var['lang']['XY_USER_REG']; ?></a></li>
<?php endif; ?>
</ul>
<div class="line"></div>