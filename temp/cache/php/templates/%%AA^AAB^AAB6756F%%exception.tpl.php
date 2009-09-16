<?php /* Smarty version 2.6.18, created on 2009-09-15 17:16:44
         compiled from file:E:%5CWebs%5CEcoleNumerique2%5Ctrunk%5Cproject/modules/public/stable/standard/default/templates/exception.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'i18n', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/standard/default/templates/exception.tpl', 6, false),array('function', 'showdiv', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/standard/default/templates/exception.tpl', 20, false),array('function', 'cycle', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/standard/default/templates/exception.tpl', 36, false),array('block', 'popupinformation', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/standard/default/templates/exception.tpl', 38, false),array('modifier', 'var_export', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/standard/default/templates/exception.tpl', 46, false),)), $this); ?>
<div class="errorMessage">
	<h1><?php echo $this->_tpl_vars['ppo']->type; ?>
</h1>
	<?php echo $this->_tpl_vars['ppo']->message; ?>

	<?php if (! is_null ( $this->_tpl_vars['ppo']->urlBack )): ?>
		<br /><br />
		<a href="<?php echo $this->_tpl_vars['ppo']->urlBack; ?>
"><?php echo smarty_function_i18n(array('key' => "generictools|messages.action.backLong"), $this);?>
</a>
	<?php endif; ?>
</div>

<?php if (( $this->_tpl_vars['ppo']->mode == 'DEVEL' || $this->_tpl_vars['ppo']->mode == 'UNKNOWN' )): ?>
	<br />
	<div class="errorMessage" style="text-align: left">
		<h1><?php echo smarty_function_i18n(array('key' => "generictools|messages.titlePage.debugInformation"), $this);?>
</h1>
		<b>Type</b> : <?php echo $this->_tpl_vars['ppo']->type; ?>
<br />
		<b>Fichier</b> : <?php echo $this->_tpl_vars['ppo']->file; ?>
<br />
		<b>Ligne</b> : <?php echo $this->_tpl_vars['ppo']->line; ?>

		
		<br /><br />
		<center>
			<?php echo smarty_function_showdiv(array('id' => ($this->_tpl_vars['ppo']->id),'show' => 'false','captioni18n' => "generictools|messages.action.debugMoreInfos"), $this);?>

		</center>
		<div id="<?php echo $this->_tpl_vars['ppo']->id; ?>
" style="display:none">
			<br />
			<?php if (count ( $this->_tpl_vars['ppo']->trace )): ?>
				<table class="CopixTable">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th>Classe</th>
						 	<th>Fonction</th>
						 	<th>Arguments</th>
						</tr>
					</thead>
					<tbody>
					<?php $_from = $this->_tpl_vars['ppo']->trace; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['item']):
?>
						<tr <?php echo smarty_function_cycle(array('values' => 'class="alternate",'), $this);?>
>
			 				<td>
			 					<?php $this->_tag_stack[] = array('popupinformation', array()); $_block_repeat=true;smarty_block_popupinformation($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			 						<?php echo smarty_function_i18n(array('key' => "generictools|messages.line"), $this);?>
 : <?php echo $this->_tpl_vars['item']['line']; ?>

			 						<br />
			 						<?php echo smarty_function_i18n(array('key' => "generictools|messages.file"), $this);?>
 : <?php echo $this->_tpl_vars['item']['file']; ?>

			 					<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_popupinformation($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			 				</td>
			 				<td><?php if (isset ( $this->_tpl_vars['item']['class'] )): ?><?php echo $this->_tpl_vars['item']['class']; ?>
<?php endif; ?></td>
			 				<td><b><?php echo $this->_tpl_vars['item']['function']; ?>
</b></td>
			 				<td><pre style="overflow: auto; max-width: 640px; max-height: 400px"><?php echo var_export($this->_tpl_vars['item']['args'], true); ?>
</pre></td>
						</tr>
					</tbody>
					<?php endforeach; endif; unset($_from); ?>
				</table>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>