<div class="loading-button">
	<a href="{copixurl dest="regroupements|villes|"}" class="button">
	<div class="button-title">
	{i18n key="regroupements|regroupements.homepage.grvilles_big"}
	</div>
	<div class="button-subtitle">
		{if $GRVILLES eq 0}{i18n key="regroupements|regroupements.homepage.grvilles_small_0"}
		{elseif $GRVILLES eq 1}{i18n key="regroupements|regroupements.homepage.grvilles_small_1"}
		{else}{i18n key="regroupements|regroupements.homepage.grvilles_small_n" nb=$GRVILLES}{/if}
	</div>
	</a>
</div>
<div class="loading-button">
	<a href="{copixurl dest="regroupements|ecoles|"}" class="button">
	<div class="button-title">
	{i18n key="regroupements|regroupements.homepage.grecoles_big"}
	</div>	
	<div class="button-subtitle">
		{if $GRECOLES eq 0}{i18n key="regroupements|regroupements.homepage.grecoles_small_0"}
		{elseif $GRECOLES eq 1}{i18n key="regroupements|regroupements.homepage.grecoles_small_1"}
		{else}{i18n key="regroupements|regroupements.homepage.grecoles_small_n" nb=$GRECOLES}{/if}
	</div>
	</a>
</div>