{if $ppo->cas_error eq "no-iconito-user"}
<div class="error">Erreur : Le login CAS n'est pas associ&eacute; &agrave; un compte Iconito.</div>
<div>&rarr; <a href="{copixurl dest="auth|cas|logout"}">D&eacute;connectez-vous</a></div>
{/if}
