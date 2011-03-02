jQuery(document).ready(function($){

    $.getJSON('/index.php/rssnotifier/default/getJson', function(data){

        $.tmpl('<li><h4><a>${title}</a></h4><p><a>${content}</a> <a class="rssNotifierLink" href="">${link}</a></p></li>', data).appendTo('#rssNotifierItems');

        $("#rssNotifierItems a.rssNotifierLink").each(function(){
            $("#rssNotifierItems a").attr('href', $(this).text());
            $(this).html("Lire la suite...");
        });

    });
    
    
    var sliderWidth = $("#rssNotifier").width();
    var elementWidth = 630; //$("#rssNotifierItems li").width();
    var nbElement = 5; //$("#rssNotifierItems a").size();
    var imageReelWidth = elementWidth * nbElement;
    
    $('#rssNotifier').append('<ul id="rssNotifierSteps"><li><a href="#" rel="1" class="active"><span>1</span></a></li><li><a href="#" rel="2"><span>2</span></a></li><li><a href="#" rel="3"><span>3</span></a></li><li><a href="#" rel="4"><span>4</span></a></li><li><a href="#" rel="5"><span>5</span></a></li></ul>');
    $('#rssNotifierItems').css('width', imageReelWidth);
    
    // Rotation
    rotate = function (){
        var triggerID = $active.attr("rel") - 1; //Get number of times to slide
        var next_pos = triggerID * elementWidth; //Determines the distance the image reel needs to slide
    
        $("#rssNotifierSteps a").removeClass('active'); //Remove all active class
        $active.addClass('active'); //Add active class (the $active is declared in the rotateSwitch function)
        
        //Slider Animation
        $("#rssNotifierItems").animate({ 
            left: -next_pos
        }, 500 );
    }; 

    //Rotation + Timing Event
    rotateSwitch = function(){        
        play = setInterval(function(){ //Set timer - this will repeat itself every 3 seconds
            $active = $('#rssNotifierSteps a.active').parent('li').next().children();
            if ( $active.length === 0) { //If paging reaches the end...
                $active = $('#rssNotifierSteps a:first'); //go back to first
            }
            rotate(); //Trigger the paging and slider function
        }, 5000); //Timer speed in milliseconds 
    };

    rotateSwitch(); //Run function on launch
    
    //On Hover
    $("#rssNotifier").hover(function() {
        clearInterval(play); //Stop the rotation
    }, function() {
        rotateSwitch(); //Resume rotation
    });    
    
    //On Click
    $('#rssNotifierSteps a').click(function(){
        $active = $(this); //Activate the clicked paging
        //Reset Timer
        clearInterval(play); //Stop the rotation
        rotate(); //Trigger rotation immediately
        rotateSwitch(); // Resume rotation
        return false;
    });

});