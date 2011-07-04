function addPrefixToQuery(prefix,ns){
	alert("PREFIX lines will be automatically added to a query if a prefix detected within the query is listed in \"Useful Prefixes\".\n\nE.g. If your query contains \""+prefix+":Entity\", the line: \n\n\tPREFIX "+prefix+": <"+ns+">\n\nwill appear at the top of the query, after you submit.");
}
function showPrefixes(){
        document.getElementById("prefixes").style.display = "block";
        document.getElementById("prefixes_show").style.display = "none";
        document.getElementById("prefixes_hide").style.display = "block";
}
function hidePrefixes(){
        document.getElementById("prefixes").style.display = "none";
        document.getElementById("prefixes_show").style.display = "block";
        document.getElementById("prefixes_hide").style.display = "none";
}

