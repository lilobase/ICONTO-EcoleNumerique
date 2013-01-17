<div>
	<h2>{i18n key="blog.nav.search"}</h2>
	<form action="{copixurl dest="blog||listArticle"}" method="get" name="recherche">
		<input type="hidden" name="blog" value="{$blog->url_blog}" />
		<input type="text" id="searchBlog" name="critere" />
		<input type="submit" value="{i18n key="blog.buttons.ok"}" />
	</form>
</div>