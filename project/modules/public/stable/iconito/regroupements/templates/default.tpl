<div id="carres">
<div class="carres_menu">

<a href="{copixurl dest="regroupements|villes|"}">
	{i18n key="regroupements|regroupements.homepage.grvilles_big"}
	<span>
		{if $GRVILLES eq 0}{i18n key="regroupements|regroupements.homepage.grvilles_small_0"}
		{elseif $GRVILLES eq 1}{i18n key="regroupements|regroupements.homepage.grvilles_small_1"}
		{else}{i18n key="regroupements|regroupements.homepage.grvilles_small_n" nb=$GRVILLES}{/if}
	</span>
</a>

<a href="{copixurl dest="regroupements|ecoles|"}">
	{i18n key="regroupements|regroupements.homepage.grecoles_big"}
	<span>
		{if $GRECOLES eq 0}{i18n key="regroupements|regroupements.homepage.grecoles_small_0"}
		{elseif $GRECOLES eq 1}{i18n key="regroupements|regroupements.homepage.grecoles_small_1"}
		{else}{i18n key="regroupements|regroupements.homepage.grecoles_small_n" nb=$GRECOLES}{/if}
	</span>
</a>

</div>
</div>