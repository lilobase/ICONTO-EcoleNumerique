<?php /* Smarty version 2.6.18, created on 2009-09-16 10:28:03
         compiled from file:E:%5CWebs%5CEcoleNumerique2%5Ctrunk%5Cproject/modules/public/stable/iconito/public/templates/getlistblogs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'i18n', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/iconito/public/templates/getlistblogs.tpl', 8, false),)), $this); ?>


<div class="" align="right">

<form action="index.php" method="get">
<input type="hidden" name="module" value="public" />
<input type="hidden" name="action" value="getListBlogs" />
<?php echo smarty_function_i18n(array('key' => "public.blog.form.search.lib"), $this);?>
 :
<input type="text" name="kw" class="form" style="width: 120px;" value="<?php echo $this->_tpl_vars['kw']; ?>
" onfocus="this.select();" />
<input type="submit" value="<?php echo smarty_function_i18n(array('key' => "public.blog.form.search.submit"), $this);?>
" class="form_button" />
</form>

</div>






<?php echo $this->_tpl_vars['list']; ?>


