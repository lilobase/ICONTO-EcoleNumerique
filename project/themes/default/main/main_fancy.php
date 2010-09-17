<?php
/*
	@file 		main_fancy.php
	@desc		Main Fancy constructor (POPUPS with overlay)
	@version 	1.0.0b
	@date 		2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
	@author 	S.HOLTZ <sholtz@cap-tic.fr>

	Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/
?>
<?php $module = CopixRequest::get ('module'); ?>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/module_".$module.".css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_custom.css"); ?>" type="text/css"/>

<div id="page">
	<div id="contentmain">
		<div class="<?php echo $module; ?>">
		<?php if (isset($MENU) && $MENU) { echo CopixZone::process ('kernel|menu', array('MENU'=>$MENU, 'popup'=>true, 'canClose'=>(isset($CAN_CLOSE)?$CAN_CLOSE:false))); } ?>
		<?php echo $MAIN; ?>
		</div>
	</div>
</div>