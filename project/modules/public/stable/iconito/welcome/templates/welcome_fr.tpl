<div class="column1 ink_blue">
	<div class="welcome_pages ink_blue">{copixzone process=welcome|pages titre='' blog=edito nb=1 colonnes=1 content=true}</div>
	<div class="welcome_articles">
		<div class="articles_title">{i18n key="welcome|welcome.articles"}</div>
		<div>{copixzone process=welcome|actualites blog=edito colonnes=1 chapo=1 nb=2}
    </div>
	</div>
</div>
<div class="column2 ink_blue">
	<div class="welcome_photos">{copixzone process=welcome|photos mode=dewslider titre='' classeur=1 dossier=1 width=380 height=220 legendes=true}</div>
	<div class="welcome_blogs">{copixzone process=welcome|blogs}</div>
	<div class="welcome_ecoles">
		<div class="ecoles_title">{i18n key="welcome|welcome.ecoles"}</div>
		<div>{copixzone process=welcome|ecoles titre='' ajaxpopup=true colonnes=1 groupBy=ville grville=1 ville=0 kernellimiturl=1 dispFilter=1 dispHeader=1}</div>
	</div>
</div>