function addPrefixToQuery(prefix){
	prefix=prefix.replace('<a onclick="addPrefixToQuery(this.parentNode.innerHTML)">','')
	prefix=prefix.replace('<a onclick="addNamespaceToQuery(this.innerHTML)">','')
	prefix=prefix.replace("</a>",'')
	prefix=prefix.replace("</a>",'')
	prefix=prefix.replace('&gt;','>')
	prefix=prefix.replace('&lt;','<')
	querybox=document.getElementById('querybox').value
	document.getElementById('querybox').value=prefix+'\n'+querybox
	document.getElementById('querybox').focus()
}
function addNamespaceToQuery(ns){
        ns=ns.replace('&gt;','>')
        ns=ns.replace('&lt;','<')
        document.getElementById('querybox').value+=ns
	document.getElementById('querybox').focus()
}
