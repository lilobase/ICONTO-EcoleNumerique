jQuery(document).ready(function($){

    $.getJSON('/index.php/rssnotifier/default/getJson', function(data){

        $.tmpl('<li><h4><a>${title}</a></h4><p>${content} <a class="rssNotifierLink" href="">${link}</a></p></li>', data).appendTo('#rssNotifier');

        $("#rssNotifier a.rssNotifierLink").each(function(){
            $("#rssNotifier a").attr('href', $(this).text());
            $(this).html("Lire la suite...");
        });

    });
});