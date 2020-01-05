<?php

	///HARDCODE HERE..
	//something like
	//$hardcode_db_list = '--DB something --DB somethingelse';
	$hardcode_db_list = '';


	$script_name = array_shift($argv);
	$database_list = $argv;
	$db_arg = false;

	//first use any hardcoded value
	if(strlen($hardcode_db_list) > 0){
		$db_arg = $hardcode_db_list;
	}

	//but if arguments are present respect them instead..
	if($database_list > 0){
		$db_arg = '';
		foreach($database_list as $this_database){
			$db_arg .= " --DB=$this_database  ";
		}
	}

	//but we have to have something..
	if(!$db_arg){
		echo "Critical Error: you must either hard code the database parameters by changing this file... or enter them as the only arguments to this command\n";
		exit();
	}

exit();
$commands = [
	"php artisan DURC:mine --squash $db_arg",
	"php artisan DURC:write ",
	"cp routes/starting.web.php routes/web.php",
	"cat routes/web.durc.php | tail -n +2 >> routes/web.php",
	"cat routes/ending.web.php >> routes/web.php",
	"composer clear-cache",
	"composer dump-autoload",
];


	foreach($commands as $this_command){
		echo "Running: $this_command\n";
		system($this_command,$return_status);
		
		if($$return_status < 0){ //then it retueded an error!!
			echo "Error: $this_command failed... returned $return_status stopping\n";
			exit(-1);
		}
	}



