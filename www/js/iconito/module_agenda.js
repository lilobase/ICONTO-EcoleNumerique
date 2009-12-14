function displayPopupEvent (id) {
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
        popup.style.top=(y+22)+"px";
        popup.style.zIndex = 999;
        popup.innerHTML = html.innerHTML;
        popup.style.visibility = "visible";
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
//      overPopup = true;
}

function mouseOutPopupEvent () {
        //hidePopupEvent (1);
        //overPopup = false;
}