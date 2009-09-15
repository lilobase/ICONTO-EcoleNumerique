<div>
	<h2>{i18n key="blog.nav.search"}</h2>
	<form action="index.php" method="get" name="recherche">
		<input type="hidden" name="module" value="blog" />
		<input type="hidden" name="action" value="listArticle" />
		<input type="hidden" name="blog" value="{$blog->url_blog}" />
		<input type="text" size="18" name="critere" />
		<input type="submit" value="{i18n key="blog.buttons.ok"}" />
	</form>
</div>