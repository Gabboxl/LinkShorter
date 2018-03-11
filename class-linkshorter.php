<?php

//linkshorter class made by gabboxl (t.me/gabbo_xl) under license GNU AGPLv3




#		adfly credentials

	//Your adfly api key
	$apiKeyAdfly = '3f885db2f68d068a6015d4f132bf37b8';
	// Your user id
	$uIdAdfly = 16175835;
    

#		bitly credentials

    //your bitly username
    $userbitly = "bitly username here";
    //your bitly apikey
    $keybitly = "bitly apikey here";
	
#		google crednetials
    // Get API key from : http://code.google.com/apis/console/
    	$apiKeygoogle = 'your api key here';
	
    

	class linkshorter {
		private $service;
        private $link;
        
        
        
        
		function __construct($service, $link, $domain = null, $advert_type = null) {
            
            if ($service == "adfly" || $service == "bitly" || $service == "googl" and $link != "" or $link != null) {
            $this->$service($link, $domain, $advert_type);
        } else {
            $this->setError("Invalid service: $service or link not set");
            return;
			exit;
        }
 		}
        
        
	private function setError($msg) {
        $this->error = $msg;
        $this->hasError = true;
    }
    
    function getError() {
        return $this->error;
    }
    
    function getLink() {
    	return $this->response;
    }
        
      private function adfly($url, $domain = 'adf.ly', $advert_type = 'int') {
      global $apiKeyAdfly;
      global $uIdAdfly;
      
	  // base api url
	  $api = 'http://api.adf.ly/api.php?';
	
	  // api queries
	  $query = array(
	    'key' => $apiKeyAdfly,
	    'uid' => $uIdAdfly,
	    'advert_type' => $advert_type,
	    'domain' => $domain,
	    'url' => $url
	  );
	
	  // full api url with query string
	  $api = $api . http_build_query($query);
	  // get data
	  if ($data = file_get_contents($api))
	    $this->response = $data;
	}
	

	private function googl($url) {
    global $apiKeygoogle;
    
	$postData = array('longUrl' => $url);
	$jsonData = json_encode($postData);

	$curlObj = curl_init();

	curl_setopt($curlObj, CURLOPT_URL, "https://www.googleapis.com/urlshortener/v1/url?key=$apiKeygoogle");
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curlObj, CURLOPT_HEADER, 0);
	curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	curl_setopt($curlObj, CURLOPT_POST, 1);
	curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

	$response = curl_exec($curlObj);

	// Change the response json string to object
	$json = json_decode($response);

	curl_close($curlObj);

	$this->response   =    $json->id;
    }


	private function bitly($url) {
    
    global $userbitly;
    global $keybitly;
    
	    $query = array(
	        "version" => "2.0.1",
	        "longUrl" => $url,
	        "login" => $userbitly, // replace with your login
	        "apiKey" => $keybitly // replace with your api key
	    );
	
	    $query = http_build_query($query);
	
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "http://api.bit.ly/shorten?".$query);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	    $response = curl_exec($ch);
	    curl_close($ch);
	
	    $response = json_decode($response);
	
	    if($response->errorCode == 0 && $response->statusCode == "OK") {
        
	        $this->response  =  $response->results->{$url}->shortUrl;
            
	    } else {
	        return null;
	    }
	}



   		
}








