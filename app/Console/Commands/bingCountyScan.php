<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class bingCountyScan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bing:county_scan {search_string}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Using the healthdept data point, using Bing API for a search term entered on the command line ';

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
	//we need a search term to include in our results...
        $search_term = $this->argument('search_string');

	if(is_null($search_term)){
		$this->error("Search term is null");
		exit();
	}
	
	$this->info("Running a search on the departmnet of public health websites for the terms '$search_term'");

	//need a list of every county public health website in the country
	$all_public_health = \App\healthdept::all();

	//we need to search bing.. so..
        $bing_api_key = env('BING_API_KEY',false);
        $bing_azure_url = env('BING_AZURE_DOMAIN',false);

	//make sure the keys are good..
        if(!$bing_api_key){
                echo $this->error("Fail: BING_API_KEY is not set in the .env file..\n");
                exit();
        }

        if(!$bing_azure_url){
                $this->error("Fail: BING_AZURE_DOMAIN is not set in the .env file..\n");
                exit();
        }

	//use the keys to make our Bing Search object..
	$bing = new \App\BingWebSearch($bing_api_key,$bing_azure_url);



	foreach($all_public_health as $this_county){
	
		$healthdept_id = $this_county->id;
	
		//bing does not allow deep-url site searches... so we need to convert to a domain name...
		$domain = parse_url($this_county->healthdept_url, PHP_URL_HOST);
	
		//basically, we want to do a site search.. plus the term
		$bing_search_string = "site:$domain $search_term";
		$this->info("searching with\t $bing_search_string");

	}

	$response_data = $bing->doSearch($bing_search_string);

        if(isset($response_data['webPages']['value'])){
                $resulting_pages = $response_data['webPages']['value'];
        }else{
                $this->error("Fail: Got results, but they do not include webPages as we expect");
                exit();
        }

        $result_count = count($resulting_pages);
        $this->info("Got $result_count results fom the Bing API");
        foreach($resulting_pages as $this_page){
                $url = $this_page['url'];
		$this->info("$bing_search_string \t\t$url");

		//ok lets save this data...
		$healthdept_url = \App\healthdept_url::create([
			'url' => $url,
			'search_term' => $search_term,
			'healthdept_id' => $healthdept_id,
			]
			);
	
		$healthdept_url->save();

	}

	






    }
}
