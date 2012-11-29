<?php
/*
 Copyright 2012 Adam Darlow and Gideon Goldin

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 */


class tempStorage {
	
	var $key = NULL;

    /**
	 * Constructor
	 * 
	 * @param encryptionKey - key to use for encrypting and decrypting stored values
	 */
    function tempStorage($encryptionKey = null) {
                           
        $this->key = ($encryptionKey == null) ? constant('KEY') : $encryptionKey;
	}
						 
	/**
	 * store - store a name/value pair
	 * 
	 * @param name - string
	 * @param value
	 * @param overwrite - overwrite existing values (true) or fail (false - default)
	 * 
	 * @return true if succeeded, false if failed
	 */
	function store($name, $value, $overwrite = false) {
		
		if ((! $overwrite) and $this->retreive($name)) {
			return false;
		}
		
	    $encryptedValue = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, 
	        md5($this->key), $value, MCRYPT_MODE_CBC, md5(md5($this->key))));

        setcookie($name, $encryptedValue, time() + (24 * 60 * 60), '/');
		
		return true;
    }
						   
	/**
	 * retrieve - retrieve a stored value
	 * 
	 * @param name - string
	 * 
	 * @return stored value if found, null otherwise
	 */
	function retreive($name) {	
		if($name == null or !isset($_COOKIE[$name])) {
			return null;
		}
		
	    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->key), base64_decode($_COOKIE[$name]), MCRYPT_MODE_CBC, md5(md5($this->key))), "\0");
    }
						  
	/**
	 * clear - remove the name (and any stored value) from the storage
	 * 
	 * @param name - string
	 */ 
	function clear($name) {
	    setcookie($name, '', time()-3600, '/');		
    }
}

?>