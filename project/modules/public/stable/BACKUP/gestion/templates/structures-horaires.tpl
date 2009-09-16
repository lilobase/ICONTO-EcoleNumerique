<form method="POST" action="">
<input type="hidden" name="id" value="{$ppo->structure_id}" />
<input type="hidden" name="submit" value="1" />


{html_options name=do options=$ppo->combo_do selected=$ppo->rForm->type}
:
{html_options name=debut_heure options=$ppo->combo_heures selected=$ppo->rForm->type}h{html_options name=debut_minute options=$ppo->combo_minutes selected=$ppo->rForm->type}
&agrave;
{html_options name=fin_heure options=$ppo->combo_heures selected=$ppo->rForm->type}h{html_options name=fin_minute options=$ppo->combo_minutes selected=$ppo->rForm->type}
aux jours coch&eacute;s ci-dessous
<input type="submit" />

{$ppo->MAIN}

</form>