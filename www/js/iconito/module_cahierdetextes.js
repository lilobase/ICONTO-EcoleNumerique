jQuery(document).ready(function($){

    var elementWidth = $("#memos-list ul.memo").width();
    var nbElement = $('#memos-list ul.memo li').length;
	var left = (nbElement > 10) ? 10 : ((nbElement > 5) ? 20 : 40);
    var imageReelWidth = elementWidth * nbElement;
	var buttons ='';
	for (var i=0; i<nbElement; i++)
	{
		buttons += '<li><a href="#" rel="'+(i+1)+'"><span>'+(i+1)+'</span></a></li>';
	}
	
	$('#memos-list').prepend('<ul id="memosSteps">'+buttons+'</ul>');
    $('#memos-list ul#memosSteps li a:first').addClass('active');
    $('#memos-list ul#memosSteps').css('left',left+'%');
    $('#memos-list ul.memo').css('width', imageReelWidth);
	$('#memos-list ul.memo li').css('width', elementWidth-20);
    
	// Traitement des contenus des mémos
	$.each($('#memos-list ul.memo li'), function() {
		var content = $(this).children('a').html();
		//On enlève les balises Html qui pourraient trainer
		content.replace(/<.+?>/g,'');
		if (content.length > 100)
		{
			// On coupe au prochain espace suivant les 50 premiers caractères pour ne pas couper de mot
			content = content.match(/^.{50}(\S+)+?/gm);
			$(this).children('a').html(content+' (...)');
		}
		else
			$(this).children('a').html(content);
	});
	
    // Rotation
    rotate = function (){
        var triggerID = $active.attr("rel") - 1; //Get number of times to slide
        var next_pos = triggerID * elementWidth; //Determines the distance the image reel needs to slide
    
        $("#memos-list ul#memosSteps a").removeClass('active'); //Remove all active class
        $active.addClass('active'); //Add active class (the $active is declared in the rotateSwitch function)
        
        //Slider Animation
        $("#memos-list ul.memo").animate({ 
            left: -next_pos
        }, 500 );
    }; 

    //Rotation + Timing Event
    rotateSwitch = function(){        
        play = setInterval(function(){ //Set timer - this will repeat itself every 3 seconds
            $active = $('#memos-list ul#memosSteps li a.active').parent('li').next().children();
            if ( $active.length === 0) { //If paging reaches the end...
                $active = $('#memos-list ul#memosSteps li a:first'); //go back to first
            }
            rotate(); //Trigger the paging and slider function
        }, 9000); //Timer speed in milliseconds 
    };

    rotateSwitch(); //Run function on launch
    
    //On Hover
    $("#memos-list").hover(function() {
        clearInterval(play); //Stop the rotation
    }, function() {
        rotateSwitch(); //Resume rotation
    });    
    
    //On Click
    $('#memos-list ul#memosSteps li a').click(function(){
        $active = $(this); //Activate the clicked paging
        //Reset Timer
        clearInterval(play); //Stop the rotation
        rotate(); //Trigger rotation immediately
        rotateSwitch(); // Resume rotation
        return false;
    });

});