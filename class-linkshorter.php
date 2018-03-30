<?php
/**
 * LinkShorter - A class by Gabboxl made to short links using the most famous link shortener services  under license GNU AGPLv3.
 *
 *Contacts: t.me/gabbo_xl (Telegram)
 *
 *Copyright (C) 2018  Gabboxl
 *
 *	 This program (LinkShorter) is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as published
 *    by the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program (LinkShorter) is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 **/

//		adfly credentials

    //Your adfly api key
    $apiKeyAdfly = '3f885db2f68d068a6015d4f132bf37b8';
    // Your user id
    $uIdAdfly = 16175835;

//		bitly credentials

    //your bitly username
    $userbitly = 'bitly username here';
    //your bitly apikey
    $keybitly = 'bitly apikey here';

//		google crednetials
    // Get API key from : http://code.google.com/apis/console/
        $apiKeygoogle = 'your api key here';

//		adfocus credentials key
            $adfocKey = '66ebaa219a97eac02d8690addc1d8a4e';

//		shink.me credientials
            //your shink.me user id
                $shinkid = '223430';
         //your shink.me auth_token
                $shinktoken = 'zRoCZ1';

    class linkshorter
    {
        public function __construct($service, $link, $domain = null, $advert_type = null)
        {
            if ($service == 'adfly' || $service == 'bitly' || $service == 'googl' || $service == 'adfocus' and $link != '' or $link != null) {
                $this->$service($link, $domain, $advert_type);
            } else {
                $this->setError("Invalid service: $service or link not set");

                return;
            }
        }

        private function setError($msg)
        {
            $this->error = $msg;
            $this->hasError = true;
        }

        public function getError()
        {
            if (isset($this->error)) {
                return $this->error;
            }
        }

        public function getLink()
        {
            if (isset($this->response)) {
                return $this->response;
            }
        }

        private function adfly($url, $domain = 'adf.ly', $advert_type = 'int')
        {
            global $apiKeyAdfly;
            global $uIdAdfly;

            // base api url
            $api = 'http://api.adf.ly/api.php?';

            // api queries
            $query = [
        'key'         => $apiKeyAdfly,
        'uid'         => $uIdAdfly,
        'advert_type' => $advert_type,
        'domain'      => $domain,
        'url'         => $url,
      ];

            // full api url with query string
            $api = $api.http_build_query($query);
            // get data
            $dataz = file_get_contents($api);

            if (strpos(' '.$dataz, 'http')) {
                $this->response = $dataz;
            } else {
                $jzon = json_decode($dataz, true);

                if (isset($jzon['errors'][0]['msg'])) {
                    $this->setError($jzon['errors'][0]['msg']);
                } else {
                    if (isset($jzon['warnings'][0]['msg'])) {
                        $this->setError($jzon['warnings'][0]['msg']);
                    }
                }
            }
        }

        private function adfocus($url)
        {
            global $adfocKey;

            //now add the http:// to the url if it hasn't to avoid the relative error (0)

            if (!strpos(' '.$url, 'http://') or !strpos(' '.$url, 'https://')) {
                $url = 'http://'.$url;
            }

            $adfoch = file_get_contents("http://adfoc.us/api/?key=$adfocKey&url=$url");

            if (strpos(' '.$adfoch, 'http')) {
                $this->response = $adfoch;
            } else {
                $this->setError("$adfoch (The credentials are not right OR there isn't the http(s):// before the link.)");
            }
        }

        private function shinkin($url)
        {
            global $shinkid;
            global $shinktoken;

            $data = file_get_contents("http://shink.me/stxt/0/id/$shinkid/auth_token/$shinktoken?s=$url");

            $this->response = $data;
        }

        private function googl($url)
        {
            global $apiKeygoogle;

            $postData = ['longUrl' => $url];
            $jsonData = json_encode($postData);

            $curlObj = curl_init();

            curl_setopt($curlObj, CURLOPT_URL, "https://www.googleapis.com/urlshortener/v1/url?key=$apiKeygoogle");
            curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curlObj, CURLOPT_HEADER, 0);
            curl_setopt($curlObj, CURLOPT_HTTPHEADER, ['Content-type:application/json']);
            curl_setopt($curlObj, CURLOPT_POST, 1);
            curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

            $response = curl_exec($curlObj);

            // Change the response json string to object
            $json = json_decode($response);

            curl_close($curlObj);

            $this->response = $json->id;
        }

        private function bitly($url)
        {
            global $userbitly;
            global $keybitly;

            $query = [
            'version' => '2.0.1',
            'longUrl' => $url,
            'login'   => $userbitly, // login variable
            'apiKey'  => $keybitly, // api key variable
        ];

            $query = http_build_query($query);

            $req = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://api.bit.ly/shorten?'.$query);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($req);
            curl_close($req);

            $response = json_decode($response);

            if ($response->errorCode == 0 && $response->statusCode == 'OK') {
                $this->response = $response->results->{$url}->shortUrl;
            } else {
                $this->setError($response->errorCode);
                // return null;
            }
        }
    }
