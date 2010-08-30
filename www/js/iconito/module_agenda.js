var overPopup = false;
var fadePopup;

function displayPopupEvent (id, journee) {
	var html = $(id);
	var cellhtml = $("div"+id);
	var cellPos = cellhtml.cumulativeOffset();
	
	var popup = $('popupEvent2');	
	var popupParent = popup.getOffsetParent();	
	var popupParentPos = popupParent.cumulativeOffset();
	x = cellPos[0]-popupParentPos[0];
	y = cellPos[1]-popupParentPos[1];
	var w = popup.offsetWidth; // largeur
	var windowWidth = getWindowWidth()-10;
	if(x+w>windowWidth) x = windowWidth-w-7;
	popup.style.left=(x+32)+"px";
  if (journee)
  	popup.style.top=(y+12)+"px";
  else
  	popup.style.top=(y+22)+"px";
	popup.style.zIndex = 9999;
	popup.innerHTML = html.innerHTML;
	popup.style.visibility = "visible";
}

function hidePopupEvent (force) {
	if (overPopup && !force) return false;
	var popup = $('popupEvent2');
	popup.style.visibility = "hidden";
	overPopup = false;
}

function mouseOverPopupEvent () {
	clearTimeout(fadePopup);
	overPopup = true;
}
function mouseOutPopupEvent () {
	fadePopup = setTimeout('hidePopupEvent('+1+')',1000);
}





