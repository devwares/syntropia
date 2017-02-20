
function press()
{
	document.getElementById("idImage").src = "/wp-content/plugins/syntropia/images/button-down.jpg"
	
	document.getElementById("idDivPreuve").style.display = "block";
	
	
}

function depress()
{
	document.getElementById("idDivImage").innerHTML = '<img id="idImage" src="/wp-content/plugins/syntropia/images/button-up.jpg" alt="une image d\'illusion" onMouseDown="press()" onMouseUp="depress()">'
	
	document.getElementById("idDivPreuve").style["display"] = "none";
}
