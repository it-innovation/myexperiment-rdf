function addPrefixToQuery(prefix,ns){
	alert("PREFIX lines will be automatically added to a query if a prefix detected within the query is listed in \"Useful Prefixes\".\n\nE.g. If your query contains \""+prefix+":Entity\", the line: \n\n\tPREFIX "+prefix+": <"+ns+">\n\nwill appear at the top of the query, after you submit.");
}
