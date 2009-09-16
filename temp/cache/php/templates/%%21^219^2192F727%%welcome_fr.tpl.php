<?php /* Smarty version 2.6.18, created on 2009-09-15 17:09:24
         compiled from file:E:%5CWebs%5CEcoleNumerique2%5Ctrunk%5Cproject/modules/public/stable/iconito/welcome/templates/welcome_fr.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'copixresource', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/iconito/welcome/templates/welcome_fr.tpl', 10, false),array('function', 'copixurl', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/iconito/welcome/templates/welcome_fr.tpl', 28, false),array('function', 'i18n', 'file:E:\\Webs\\EcoleNumerique2\\trunk\\project/modules/public/stable/iconito/welcome/templates/welcome_fr.tpl', 41, false),)), $this); ?>

<table class="welcome">
<tbody>
<tr>
<td class="ecoles">

<?php if ($this->_tpl_vars['zoneEcoles']): ?>
	<?php echo $this->_tpl_vars['zoneEcoles']; ?>

<?php else: ?>
	<img src="<?php echo smarty_function_copixresource(array('path' => "img/iconito-home2.gif"), $this);?>
" width="209" height="234" alt="Iconito vous souhaite la bienvenue" style="margin-right:20px;" />
<?php endif; ?>


</td>
<td>

<?php if ($this->_tpl_vars['zonePhotos'] || $this->_tpl_vars['zoneActualites']): ?>

	<?php echo $this->_tpl_vars['zonePhotos']; ?>


	<?php echo $this->_tpl_vars['zoneActualites']; ?>


<?php else: ?>
	<h3>Bienvenue sur Iconito, le portail numérique scolaire libre.</h3>
	
	<p>Iconito est un portail éducatif comprenant un ensemble d'outils et de ressources à destination des enseignants et des élèves, mais aussi des parents et des autres intervenants du système scolaire. Il est développé sous licence libre (GNU GPL).</p>
	
	<a class="button_like" href="<?php echo smarty_function_copixurl(array('dest' => "auth||login"), $this);?>
">Connexion &agrave; Iconito</a>
	
	<br/>
	
	</div>
	
	<br/>
	<div class="cartouche">
	<a href="<?php echo smarty_function_copixurl(array('dest' => "public||"), $this);?>
"><img class="logo" src="<?php echo smarty_function_copixresource(array('path' => "img/welcome/welcome-blog.gif"), $this);?>
" alt="Logo Blogs" border="0"/></a>
	<h4>Consultez les publications</h4>
	<p>
	Ecoles, classes, villes ou groupes de travail, ils peuvent tous publier des blogs.</p> 
	<br/>
	<a class="button_like" href="<?php echo smarty_function_copixurl(array('dest' => "public||"), $this);?>
"><?php echo smarty_function_i18n(array('key' => "public|public.blog.annuaire"), $this);?>
</a>
	<span class="rss"><a title="RSS" href="<?php echo smarty_function_copixurl(array('dest' => "public||rss"), $this);?>
"><img src="<?php echo smarty_function_copixresource(array('path' => "img/blog/feed-icon-16x16.png"), $this);?>
" width="16" height="16" border="0" alt="RSS" title="RSS" /> Flux RSS</a></span>
	</div>
	
	<br/>
	<div class="astuce"><b>Astuce</b> - Vous pouvez télécharger un logo pour votre blog. Allez dans Administration du blog, Options, Modifier, puis télécharger le logo. Une bonne taille de logo est 150 x 150 pixels par exemple!
	</div>

<?php endif; ?>
	

</td>
</tr>
</tbody>
</table>

<div class="small" style="clear:both;">
<hr/>
<?php if ($this->_tpl_vars['isDemo']): ?>
Ceci est un site de démonstration. Nous ne sommes pas responsables des contenus que les internautes peuvent publier sur ce site dans le cadre de leurs tests. Pour toute information, n'hésitez pas à nous contacter: <a href="mailto:dev@iconito.org">dev@iconito.org</a><p>
<?php else: ?>
Les dernières informations sur le développement d'Iconito sont consultables sur <a href="http://www.iconito.org">iconito.org</a>. Pour toute information, n'hésitez pas à contacter directement l'équipe des développeurs: <a href="mailto:dev@iconito.org">dev@iconito.org</a>
<?php endif; ?>
</div>