<div id="dialog">
<h2>{i18n key="charte.important" noEscape=1}</h2>
<div class="content-info">
	{i18n key="charte.read" noEscape=1}
</div>
<div class="loading-button">
	<a class="button button-charte iframe" href="{$ppo->url}">
		{i18n key="charte.view" noEscape=1}
	</a>
</div>
<div class="content-panel center">
	<a class="button button-confirm" href="{copixurl dest="charte|charte|redirect" typeAction="accept"}">
	{i18n key="charte.yes" noEscape=1}
	</a>
</div>
<div class="content-panel center">
	<a class="button button-cancel" href="{copixurl dest="charte|charte|redirect" typeAction="reject"}">
	{i18n key="charte.no" noEscape=1}
	</a>
</div>
</div>