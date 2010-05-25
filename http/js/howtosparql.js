function showResults(results){
        document.getElementById(results).style.display = "block";
        document.getElementById(results+"_show").style.display = "none";
	document.getElementById(results+"_hide").style.display = "block";
}
function hideResults(results){
        document.getElementById(results).style.display = "none";
        document.getElementById(results+"_show").style.display = "block";
        document.getElementById(results+"_hide").style.display = "none";
}
