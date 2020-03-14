<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class testBing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bing:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test that your command is working';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $bing_api_key = env('BING_API_KEY',false);
	$bing_azure_url = env('BING_AZURE_DOMAIN',false);

	if(!$bing_api_key){
		echo $this->error("Fail: BING_API_KEY is not set in the .env file..\n");
		exit();
	}

	if(!$bing_azure_url){
		$this->error("Fail: BING_AZURE_DOMAIN is not set in the .env file..\n");
		exit();
	}

	$this->info("Correctly loaded configuration");

	//this should error
	//$bing = new \App\BingWebSearch();	
	//and it does...

	$bing = new \App\BingWebSearch($bing_api_key,$bing_azure_url);

	$search_term = "Bill Gates"; //this is what the test tool on the Azure site uses.. so its simple to compare
		//https://dev.cognitive.microsoft.com/docs/services/f40197291cd14401b93a478716e818bf/operations/56b4447dcf5ff8098cef380d/console

	$response_data = $bing->doSearch($search_term);

	if(isset($response_data['webPages']['value'])){
		$resulting_pages = $response_data['webPages']['value'];
	}else{
		$this->error("Fail: Got results, but they do not include webPages as we expect");
		exit();
	}	

	$has_wikipedia_results = false;

	$result_count = count($resulting_pages);
	$this->info("Got $result_count results fom the Bing API");
	foreach($resulting_pages as $this_page){
		$url = $this_page['url'];
		//echo "$url\n";
		if(strpos($url,'en.wikipedia.org') !== false){
			$has_wikipedia_results = true;
		}
	}

	if($has_wikipedia_results){
		$this->info("Looks like everything is working. Search is done and it included wikipedia results");
	}else{
		$this->error("The results are there, but we did not get a wikipedia page in the results");
	}


    }
}
