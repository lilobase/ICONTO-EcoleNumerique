jQuery(document).ready(function($){

    $.getJSON('/index.php/rssnotifier/default/getJson', function(datas){

	var items = '';
	var buttons = '';
	var i = 1;
	
	$.each(datas, function(index,data) {
		items += '<li><h4><a href="'+data.link+'">'+data.title+'</a></h4><p><a href="'+data.link+'">'+data.content+'</a> <a class="rssNotifierLink" href="'+data.link+'">Lire la suite...</a></p></li>';
		buttons += '<li><a href="#" rel="'+i+'"><span>'+i+'</span></a></li>';
		i++;
	});
	
	$('#rssNotifierItems').append(items);
	$('#rssNotifier').append('<ul id="rssNotifierSteps">'+buttons+'</ul>');

    var sliderWidth = $("#rssNotifier").width();
    var elementWidth = sliderWidth;
    var nbElement = datas.length;
    var imageReelWidth = elementWidth * nbElement;

    $('#rssNotifierSteps li a:first').addClass('active');
    $('#rssNotifierItems').css('width', imageReelWidth);
    $('#rssNotifierItems li').css('width', elementWidth-20);
    

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
        }, 9000); //Timer speed in milliseconds 
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

});