function fix_http(url_str) {
	  if (url_str == 'test') {
	  	return url_str;
	  }
	  
    if (url_str.length > 0 && url_str.indexOf("http") != 0) {
        url_str = "http://" + url_str;
    }
		
	return url_str;
}
