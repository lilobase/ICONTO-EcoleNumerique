

<div class="" align="right">

<form action="index.php" method="get">
<input type="hidden" name="module" value="public" />
<input type="hidden" name="action" value="getListBlogs" />
{i18n key="public.blog.form.search.lib"} :
<input type="text" name="kw" class="form" style="width: 120px;" value="{$kw}" onfocus="this.select();" />
<input type="submit" value="{i18n key="public.blog.form.search.submit"}" class="form_button" />
</form>

</div>






{$list}


