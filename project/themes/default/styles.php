<?php
/*
	@file 		styles.php
	@desc		styles loader
	@version 	1.0.0b
	@date 		2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
	@author 	S.HOLTZ <sholtz@cap-tic.fr>

	Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/
?>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_layout.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_zones.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_colors.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_typography.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_debug.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/jquerycss/default/jquery-ui-1.8.2.custom.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("js/fancybox/jquery.fancybox-1.3.1.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/module_kernel.css"); ?>" type="text/css"/>
<? if (isset($ENpopup) && $ENpopup) { ?><link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/popup.css"); ?>" type="text/css"/><? } ?>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/print.css"); ?>" type="text/css" media="print"/>

