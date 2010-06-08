<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_blog_admin.css"}" />
{literal}
<script type="text/javascript">
//<![CDATA[

function change_style (obj) {

	var custom_1 = getRef ('custom_1');
	var custom_0 = getRef ('custom_0');
	if (obj.value==1) { // Personnalisation
		custom_1.className = 'show';
		custom_0.className = 'hidden';
	} else {
		custom_1.className = 'hidden';
		custom_0.className = 'show';
	}
}


//]]>
</script>
{/literal}

{if $showErrors}
<div class="errorMessage">
<h1>{i18n key=copix:common.messages.error}</h1>
 {ulli values=$errors}
</div>
{/if}

<h1>{i18n key="blog.get.editstyle.blog.title"}</h1>


<form name="blogEdit" action="{copixurl dest="blog|admin|validBlogStyle" kind=$kind}" method="post" class="">

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">

   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blog.fields.id_ctpt'}</td>
	  	<td CLASS="form_saisie">


    <input ONCHANGE="change_style(this);" TYPE="radio" VALUE="0" NAME="style_blog_file"{if $blog->style_blog_file==0} CHECKED{/if}>{i18n key='dao.blog.fields.style_blog_file0'}<br>
    <input ONCHANGE="change_style(this);" TYPE="radio" VALUE="1" NAME="style_blog_file"{if $blog->style_blog_file==1} CHECKED{/if}>{i18n key='dao.blog.fields.style_blog_file1'}<br>


</td>
   </tr>
   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blog.fields.style_blog_file'}</td>
	  	<td CLASS="form_saisie">
			<DIV ID="custom_1" NAME="custom_1" CLASS="{if $blog->style_blog_file==0}hidden{else}show{/if}" STYLE="width:600px;">
			<textarea style="width:600px; height: 600px;" name="style_blog_file_src" class="form">{$style_blog_file_src|escape}</textarea>
			
			</DIV>
			<DIV ID="custom_0" NAME="custom_0" CLASS="{if $blog->style_blog_file==1}hidden{else}show{/if}" STYLE="width:600px;">{i18n key='dao.blog.fields.style_blog_file_not'}</DIV>
			
</td>
   </tr>

	 <tr><td colspan="2" CLASS="form_submit">
<input type="hidden" name="id_blog" value="{$blog->id_blog}">
<input type="submit" class="form_button" value="{i18n key="copix:common.buttons.ok"}" />
{if ($kind==null) or ($id_blog==null)}
	<input class="form_button" type="button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|listBlog"}'" />
{else}
	<input class="form_button" type="button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />
{/if}
	 
	</td>	 
	 </tr>
	 
	 </table>
</form>