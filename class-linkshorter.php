<?php


#		adfly credentials

	//Your adfly api key
	$apiKeyAdfly = '3f885db2f68d068a6015d4f132bf37b8';
	// Your user id
	$uIdAdfly = 16175835;
    


#		bitly credentials

    //your bitly username
    $userbitly = "gabboxl";
    //your bitly apikey
    $keybitly = "R_015b26e7107b4c7c9ec11723ada42df6";
	
    

	class linkshorter {
		private $service;
        private $link;
        
        
        
        
		function __construct($service, $link, $domain, $advert_type) {
            
            if ($service == "adfly" || $service == "bitly") {
            $this->$service($link, $domain, $advert_type);
        } else {
            $this->setError("Invalid service: $service");
            return;
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

$a = new linkshorter("bitly", "http://google.com");

echo $a->getLink();







