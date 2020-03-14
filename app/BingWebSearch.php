<?php
/*
	A class to abstract the searches against the Bing Web Search URI


*/

namespace App;


class BingWebSearch  {

	private $api_key = null;
	private $azure_domain = null;

	//the constructure accepts the api key and the azure domain as arguments and adds them to local variables..
	function __construct($api_key = null, $azure_domain = null){

		//we need both of these.
		if(is_null($api_key) || is_null($azure_domain)){
			$error  ="BingWebSearch has to have a api_key and azure_domain in the creation";
			throw new \Exception($error);
		}

		//we have them, so lets set them as local variables..
		$this->api_key = $api_key;
		$this->azure_domain = $azure_domain;
	
	}

	/**
	*	Does a Bing Web Search and returns the resulting data as an associative array
	*
	*	@param string $search_term This is the phrase you are searching for (no need to urlencode this, the function will do that for you
	*	@return array $data This is the search result
	**/
	public function doSearch($search_term){

                $q = urlencode($search_term);

                //look here: https://docs.microsoft.com/en-us/rest/api/cognitiveservices-bingsearch/bing-web-api-v7-reference
                //to understand the options going in..
		//this is the version 5.0 search url... we keep this for reference...
                //$url = "https://api.cognitive.microsoft.com/bing/v5.0/search?q=$q&count=50&safeSearch=Strict&mkt=en-us";

		//now the Bing service is based on a subscription which seems to mean that they give you a unique 
		//domain name for running the search...
		$url = "https://$this->azure_domain/bing/7.0/search?q=$q&count=50&safeSearch=Strict&mkt=en-us";
		$url = "https://api.cognitive.microsoft.com/bing/v7.0/search?q=$q&count=50&safeSearch=Strict&mkt=en-us";

		echo "using $url\n";
	
		$api_key = $this->api_key;

		echo "using $api_key\n";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$url);
                //curl_setopt($ch, CURLOPT_POST, 1);
                //curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  //Post Fields
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $headers = [
                        "Ocp-Apim-Subscription-Key: $api_key",
                //      'X-Apple-Store-Front: 143444,12',
                //      'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        //              'Accept-Encoding: gzip, deflate',
        //              'Accept-Language: en-US,en;q=0.5',
        //              'Cache-Control: no-cache',
        //              'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
        //              'Host: www.example.com',
        //              'Referer: http://www.example.com/index.php', //Your referrer address
        //              'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
        //              'X-MicrosoftAjax: Delta=true'
                ];

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//this command does the work of getting the data from the api..
                $server_output = curl_exec ($ch);
  

		//and the rest of this is here just to make sure it worked reasonably well. 
		//adapted in part from: https://stackoverflow.com/a/51405324
  		if ($server_output === false) {
        		$error = "CURL Error: " . curl_error($ch);
			throw new \Exception($error);
    		}

    		$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    		/*
     		* 4xx status codes are client errors
     		* 5xx status codes are server errors
     		*/
    		if ($responseCode >= 400) {
        		$error = "HTTP Error: " . $responseCode;
			throw new \Exception($error);
    		}

                curl_close ($ch);

                $data = json_decode($server_output,true);


		return($data);

	}
}
