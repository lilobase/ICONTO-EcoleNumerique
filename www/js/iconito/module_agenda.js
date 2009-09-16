
/*
function displayPopupEvent(id) {
   document.getElementById(id).style.visibility = 'visible';
}
function hidePopupEvent(id) {
   document.getElementById(id).style.visibility = 'hidden';
}
function toggleDisplayPopupEvent(id) {
   if (document.getElementById(id).style.visibility=='hidden') {
      document.getElementById(id).style.visibility='visible';
   }else{
      document.getElementById(id).style.visibility='hidden';
   }
   if (document.getElementById(id).style.display=='none') {
      document.getElementById(id).style.display='';
   }else{
      document.getElementById(id).style.display='none';
   }
   return false;
}
*/

var overPopup = false;

function displayPopupEvent (id, decalage, margeHaut, lieu) {
	//if (overPopup) return false;
	var obj = $('popupEvent2');	
	var html = $(id);	
  x = lastMouseX;
	y = lastMouseY;	
	/*x = decalage;
	y = margeHaut;
	*/
	var w = obj.offsetWidth; // largeur
	var windowWidth = getWindowWidth()-10;
	//alert ("x="+x+" / y="+y+" / wW="+windowWidth);
	if(x+w>windowWidth) x = windowWidth-w-7;
	obj.style.left=(x-5)+"px";
	obj.style.top=(y-10)+"px";
	obj.style.zIndex = 999;
	obj.innerHTML = html.innerHTML;
	obj.style.visibility = "visible";
}

function hidePopupEvent (force) {
	if (overPopup && !force) return false;
	var obj = $('popupEvent2');
	//obj = gProfilElm;
	//obj.style.top="0px";
	//obj.innerHTML = 'XXX';
	obj.style.visibility = "hidden";
}

function mouseOverPopupEvent () {
	overPopup = true;
}
function mouseOutPopupEvent () {
	//hidePopupEvent (1);
	//overPopup = false;
}

