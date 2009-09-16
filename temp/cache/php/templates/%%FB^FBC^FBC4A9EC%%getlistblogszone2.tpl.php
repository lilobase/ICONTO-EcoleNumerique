<?php /* Smarty version 2.6.18, created on 2009-09-16 10:28:03
         compiled from file:E:%5CWebs%5CEcoleNumerique2%5Ctrunk%5Cproject/modules/public/stable/iconito/public/templates/getlistblogszone2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'i18n', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/iconito/public/templates/getlistblogszone2.tpl', 6, false),array('function', 'counter', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/iconito/public/templates/getlistblogszone2.tpl', 8, false),array('function', 'copixurl', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/iconito/public/templates/getlistblogszone2.tpl', 13, false),array('modifier', 'datei18n', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/iconito/public/templates/getlistblogszone2.tpl', 16, false),)), $this); ?>

<div id="blogs">

	<?php if ($this->_tpl_vars['list'] != null): ?>

		<div class="blog_colonne_titre"><?php echo smarty_function_i18n(array('key' => "public.blog.listGroupes"), $this);?>
 :</div>

		<?php echo smarty_function_counter(array('assign' => 'i','name' => 'i'), $this);?>

	
		<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
			<div class="blogBody">
			<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td valign="top">
			<a class="title" href="<?php echo smarty_function_copixurl(array('dest' => "blog||listArticle",'blog' => $this->_tpl_vars['item']->url_blog), $this);?>
"><?php echo $this->_tpl_vars['item']->name_blog; ?>
</a><a class="" href="<?php echo smarty_function_copixurl(array('dest' => "blog||listArticle",'blog' => $this->_tpl_vars['item']->url_blog), $this);?>
" target="_BLANK"><img alt="<?php echo smarty_function_i18n(array('key' => "public.openNewWindow"), $this);?>
" title="<?php echo smarty_function_i18n(array('key' => "public.openNewWindow"), $this);?>
" border="0" width="12" height="12" src="img/public/open_window.png" hspace="4" /></a>
			<div class="blogType"><?php echo $this->_tpl_vars['item']->type; ?>
 <?php if ($this->_tpl_vars['item']->parent): ?>(<?php echo $this->_tpl_vars['item']->parent; ?>
)<?php endif; ?></div>
			<div class="blogStats"><?php if (! $this->_tpl_vars['item']->stats['nbArticles']['value']): ?><?php echo smarty_function_i18n(array('key' => "public.blog.0article"), $this);?>
<?php elseif ($this->_tpl_vars['item']->stats['nbArticles']['value'] > 1): ?><?php echo smarty_function_i18n(array('key' => "public.blog.Narticle",'1' => $this->_tpl_vars['item']->stats['nbArticles']['value']), $this);?>
<?php else: ?><?php echo smarty_function_i18n(array('key' => "public.blog.1article"), $this);?>
<?php endif; ?>
			<?php if ($this->_tpl_vars['item']->stats['lastUpdate']['value']): ?> - <?php echo smarty_function_i18n(array('key' => "public.blog.lastUpdate",'1' => ((is_array($_tmp=$this->_tpl_vars['item']->stats['lastUpdate']['value'])) ? $this->_run_mod_handler('datei18n', true, $_tmp, 'date_short_time') : smarty_modifier_datei18n($_tmp, 'date_short_time'))), $this);?>
<?php endif; ?></div>
			</td>
			<td align="right" valign="top">
			<?php if ($this->_tpl_vars['item']->logo_blog): ?><div><img class="logo" src="<?php echo smarty_function_copixurl(array('dest' => "blog|admin|logo",'id_blog' => $this->_tpl_vars['item']->id_blog), $this);?>
" border="0" /></div><?php endif; ?>
			</td></tr></table>
			</div>
			<?php echo smarty_function_counter(array('name' => 'i'), $this);?>

		<?php endforeach; endif; unset($_from); ?>
		
		<div class="blogBody">
			<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td valign="top">
			<div class="blogStats"><a title="RSS" href="<?php echo smarty_function_copixurl(array('dest' => "public||rss"), $this);?>
"><img src="img/blog/feed-icon-16x16.png" width="16" height="16" border="0" alt="RSS" title="RSS" align="left" hspace="4" /> <?php echo smarty_function_i18n(array('key' => "public.rss.link"), $this);?>
</a></div>
			</td>
			<td></td></tr></table>
			</div>
		
		
		
		
	<?php else: ?>
		
		<?php echo smarty_function_i18n(array('key' => "public.blog.noBlogs"), $this);?>

	
	<?php endif; ?>

</div>



<div id="blogs_ecoles">
<?php if ($this->_tpl_vars['villes'] != null): ?>

	<div class="blog_colonne_titre"><?php echo smarty_function_i18n(array('key' => "public.listEcoles"), $this);?>
 :</div>
	<?php $_from = $this->_tpl_vars['villes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ville']):
?>
		<div class="ville">
		<div class="nom"><?php echo $this->_tpl_vars['ville']['nom']; ?>
</div>

		<?php $this->assign('villeid', $this->_tpl_vars['ville']['id']); ?>
		<?php $this->assign('ec', $this->_tpl_vars['ecoles'][$this->_tpl_vars['villeid']]); ?>

		<?php if ($this->_tpl_vars['ec']): ?>
			<?php $_from = $this->_tpl_vars['ec']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ecole']):
?>
				<div>
								<a title="Fiche &eacute;cole" href="<?php echo smarty_function_copixurl(array('dest' => "fichesecoles||fiche",'id' => $this->_tpl_vars['ecole']['id']), $this);?>
"><?php echo $this->_tpl_vars['ecole']['nom']; ?>
</a><?php if ($this->_tpl_vars['ecole']['type']): ?> (<?php echo $this->_tpl_vars['ecole']['type']; ?>
)<?php endif; ?>
				</div>
			<?php endforeach; endif; unset($_from); ?>
		<?php else: ?>
			<div><i><?php echo smarty_function_i18n(array('key' => "public.blog.noEcole"), $this);?>
</i></div>
		<?php endif; ?>
		</div>
		
	<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
</div>