<?php
/*
	A class to abstract the searches against the Serp Web Search URI
	https://serpapi.com/

	Need to ensure that our searches truly conform to a fair-use of Googles search results..
	The rate limiting seems to implicitly entail a limit that prevents the at-scale use that would be problematicly not fair-use.
*/

namespace App;


class SerpScholarSearch  {

	private $api_key = null;

	//the constructure accepts the api key as arguments and adds them to local variables..
	function __construct($api_key = null){

		//we need both of these.
		if(is_null($api_key)){
			$error  ="SerpScholarSearch has to have a api_key and azure_domain in the creation";
			throw new \Exception($error);
		}

		//we have them, so lets set them as local variables..
		$this->api_key = $api_key;
	
	}

	/**
	*	Does a Serp Scholar Search and returns the resulting data as an associative array
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

		//echo "using $url\n";
	
		$api_key = $this->api_key;

		//echo "using $api_key\n";

                $ch = curl_init();

		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		//from the curl that was automatically generated by the Serp export to code function...
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://serpapi.com/search');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=\"$api_key\"&engine=\"google_scholar\"&q=\"$search_terms\"&hl=\"en\""); // note hardcoding google scholar and english language.

		$headers = array();
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
    			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);


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
