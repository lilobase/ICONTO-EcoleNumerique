<?php /* Smarty version 2.6.18, created on 2009-09-15 17:07:27
         compiled from file:E:%5CWebs%5CEcoleNumerique2%5Ctrunk%5Cproject/modules/public/stable/standard/admin/templates/admin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'showdiv', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/standard/admin/templates/admin.tpl', 17, false),array('function', 'cycle', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/standard/admin/templates/admin.tpl', 26, false),array('function', 'i18n', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/standard/admin/templates/admin.tpl', 28, false),array('function', 'copixresource', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/standard/admin/templates/admin.tpl', 32, false),array('function', 'copixtips', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/standard/admin/templates/admin.tpl', 42, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['ppo']->links; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['groupId'] => $this->_tpl_vars['groupInfos']):
?>
	<table>
		<tr>
			<td width="100%">
				<h2>
					<?php if (( $this->_tpl_vars['groupInfos']['icon'] )): ?>
						<img src="<?php echo $this->_tpl_vars['groupInfos']['icon']; ?>
" alt="" />
					<?php endif; ?>  
					<?php if ($this->_tpl_vars['groupInfos']['groupcaption']): ?>
						<?php echo $this->_tpl_vars['groupInfos']['groupcaption']; ?>

					<?php else: ?>
						<?php echo $this->_tpl_vars['groupInfos']['caption']; ?>

					<?php endif; ?>
				</h2>
			</td>
			<td>
				<?php echo smarty_function_showdiv(array('id' => "group_".($this->_tpl_vars['groupId'])), $this);?>

			</td>
		</tr>
	</table>
	
	<div id="group_<?php echo $this->_tpl_vars['groupId']; ?>
">
		<table class="CopixVerticalTable">
			<?php $_from = $this->_tpl_vars['groupInfos']['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['moduleIndex'] => $this->_tpl_vars['moduleInfos']):
?>
				<?php $_from = $this->_tpl_vars['moduleInfos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['linkUrl'] => $this->_tpl_vars['linkCaption']):
?>
					<tr <?php echo smarty_function_cycle(array('values' => ',class="alternate"','name' => 'alternate'), $this);?>
>
						<td width="100%">
							<a href="<?php echo $this->_tpl_vars['linkUrl']; ?>
" class="adminLink" title="<?php echo smarty_function_i18n(array('key' => "copix:common.buttons.select"), $this);?>
"><?php echo $this->_tpl_vars['linkCaption']; ?>
</a>
						</td>
						<td>
							<a href="<?php echo $this->_tpl_vars['linkUrl']; ?>
" title="<?php echo smarty_function_i18n(array('key' => "copix:common.buttons.select"), $this);?>
"
								><img src="<?php echo smarty_function_copixresource(array('path' => "img/tools/select.png"), $this);?>
" alt="<?php echo smarty_function_i18n(array('key' => "copix:common.buttons.select"), $this);?>
" border="0"
							/></a>
						</td>
					</tr>
				<?php endforeach; endif; unset($_from); ?>
			<?php endforeach; endif; unset($_from); ?>
		</table>
	</div>
<?php endforeach; endif; unset($_from); ?>

<?php echo smarty_function_copixtips(array('tips' => $this->_tpl_vars['ppo']->tips,'warning' => $this->_tpl_vars['ppo']->warning,'titlei18n' => "install.tips.title"), $this);?>