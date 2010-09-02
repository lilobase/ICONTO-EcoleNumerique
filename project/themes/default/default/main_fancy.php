<div id="page">
	<div id="contentmain">
		<div class="<?php echo $module; ?>">
		<?php if (isset($MENU) && $MENU) { echo CopixZone::process ('kernel|menu', array('MENU'=>$MENU, 'popup'=>true, 'canClose'=>(isset($CAN_CLOSE)?$CAN_CLOSE:false))); } ?>
		<?php echo $MAIN; ?>
		</div>
	</div>
</div>