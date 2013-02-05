function fix_http(url_str) {
		return fix_http(url_str, false);
}

function fix_http(url_str, folder) {
	
	  if (url_str == 'test') {
	  	return url_str;
	  }
	  
    if (url_str.length == 0) {
    	return url_str;
    }    
    
    if (url_str.indexOf("http") != 0) {
        url_str = "http://" + url_str;
    }
    
    if (folder && !(url_str.charAt(url_str.length - 1) == "/")) {
    	url_str += "/";
    }
		
		return url_str;
}
