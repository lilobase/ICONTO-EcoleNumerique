
function insertDocument (mode, url, field, format, htmlDownload, htmlView, i18n_unsupportedFormat) {
  var popup = false;
  var html = '';
  switch (format) {
    case 'wiki' :
      self.parent.current_url_doc = "[["+url+"|"+mode+"]]";
      self.parent.bblink ('', field, 80);
      break;
    
    case 'dokuwiki' :
      self.parent.current_url_doc = "{{"+url+"|_"+mode+"_}}";
      self.parent.bblink ('', field, 80);
      break;
    
    case 'fckeditor' :
    case 'ckeditor' :
    case 'html' :
      if (mode == 'view') 					html = urldecode(htmlView);
      else if (mode == 'download')	html = urldecode(htmlDownload);
      if (format == 'fckeditor')
        self.parent.add_photo_fckeditor (field, html);
      else if (format == 'ckeditor')
        self.parent.add_photo_ckeditor (field, html);
      else
        self.parent.add_html (field, html);
      break;

    //return only url
    case 'text':
      self.parent.add_text(field, url);
      break;

    default :
      alert (i18n_unsupportedFormat);
      break;
  }

}

function urldecode(ch) {
  ch = ch.replace(/[+]/g," ");
  return unescape(ch);
}

