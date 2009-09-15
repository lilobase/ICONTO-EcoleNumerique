<div id="user_logged">

	<?php if ($ppo->user->isConnected ()): ?>

		<p>		
			Utilisateur : <strong><?php echo $ppo->user->getLogin ();
			
			 ?></strong> <?php if ($ppo->user->getIdPersonnel()) echo '('.trim($ppo->user->getExtra('prenom').' '.$ppo->user->getExtra('nom')).')'; ?> - <a href="<?php echo CopixUrl::get ('auth|log|out') ?>">Se d&eacute;connecter</a>
		</p>
		
	<?php else: ?>

		<p>Non connect&eacute;</p>

	<?php endif ?>

</div>