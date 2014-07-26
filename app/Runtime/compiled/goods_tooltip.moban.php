<?php if ($this->_var['goods']['is_none'] || $this->_var['goods']['is_end']): ?>
<div id="sysmsg-tip" class="sysmsg-tip-deal-close" style="margin-bottom:35px;">
	<div class="sysmsg-tip-top"></div>
	<div class="sysmsg-tip-content">
		<div class="deal-close">
			<div class="focus"><?php echo $this->_var['lang']['XY_B_SORRY_SOLD']; ?><br />
				<?php if ($this->_var['goods']['is_none']): ?>
				<?php echo $this->_var['lang']['XY_B_SORRY_SOLD_OUT']; ?>
				<?php elseif ($this->_var['goods']['is_end']): ?>
				<?php echo $this->_var['lang']['XY_GROUP_IS_END']; ?>
				<?php endif; ?>
			</div>
			<div id="tip-deal-subscribe-body" class="body">
				<form id="tip-deal-subscribe-form" method="post" action="index.php">
					<table>
						<tr>
							<td><?php echo $this->_var['lang']['XY_HEADER_SUBS']; ?>&nbsp;</td>
							<td><input type="text" name="email" class="f-text" value="" /></td>
							<td>&nbsp;
								<input class="commit" type="submit" value="<?php echo $this->_var['lang']['XY_SUBSCRIBE']; ?>" />
								<input type="hidden" name="cityid" value="<?php echo $this->_var['cityid']; ?>" />
								<input type="hidden" name="m" value="Index" />
								<input type="hidden" name="a" value="malllist" />
								<input type="hidden" value="subScribe" name="do"/>
								<input type="hidden" value="1" name="noajax"/>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<span id="sysmsg-tip-close" class="sysmsg-tip-close"><?php echo $this->_var['lang']['XY_CLOSE']; ?></span></div>
	<div class="sysmsg-tip-bottom"></div>
</div>
<?php endif; ?>