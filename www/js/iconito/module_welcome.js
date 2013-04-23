var hpslideshow;

jQuery(document).ready(function($){

    
    sliderHeight = $("#slider_articles").height();
    elementHeight = sliderHeight;
    nbElement = $('#slider_articles ul li').size();;
    completeHeight = elementHeight * nbElement;
    
	$('#slider_articles li:first').addClass('active');

    // Rotation
    rotate = function (){
        var triggerID = $active.attr("rel") - 1; //Get number of times to slide
        var next_pos = triggerID * elementHeight; //Determines the distance the image reel needs to slide
        $('#slider_articles li').removeClass('active');
        $active.addClass('active'); //Add active class (the $active is declared in the rotateSwitch function)
        
        //Slider Animation
        $("#slider_articles_items").animate({ 
            top: -next_pos
        }, 500 );
    }; 

    //Rotation + Timing Event
    rotateSwitch = function(){        
        play = setInterval(function(){ //Set timer - this will repeat itself every 3 seconds
            $active = $('#slider_articles li.active').next();
            if ( $active.length === 0) { //If paging reaches the end...
                $active = $('#slider_articles li:first'); //go back to first
            }
            rotate(); //Trigger the paging and slider function
        }, 7000); //Timer speed in milliseconds 
    };

    rotateSwitch(); //Run function on launch
    
    //On Hover
    $("#slider_articles").hover(function() {
        clearInterval(play); //Stop the rotation
    }, function() {
        rotateSwitch(); //Resume rotation
    });    
    

});