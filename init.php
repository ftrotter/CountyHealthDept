<?php
	//this is the script that sets up Eden...

//TODO This script should check for a properly setup zermelo, and user auth system before running the installation...
// so that we can avoid the stage where we get errors because _zermelo_cache/_zermelo_config are not there
// and where JWT security does not enable because we cannot save users...

	$composer_locations = [
		'/bin/composer',
		'/usr/bin/composer',
		];

	//we assume its missing
	$is_got_composer = false;

	foreach($composer_locations as $is_it_here){
		if(file_exists($is_it_here)){
			$is_got_composer = true;
		}
	}

	if(!$is_got_composer){
		echo "Error: I could not find composer globally installed\n";
		exit();
	}

	$userinfo = posix_getpwuid(posix_geteuid());

	if(!$userinfo['name'] == 'root'){
		echo "This file needs to be run as root..\n";
		exit();
	}

	$real_user = getenv('SUDO_USER');

	echo "running as root, but from |$real_user| unix account\n";

	$composer_config_dir = "/home/$real_user/.composer";

	
	if(!file_exists($composer_config_dir)){
		echo "Error: The directory $composer_config_dir needs to exist and be writable to save authorizations etc... \n";
		exit();
	}



	$cmds = [
		"sudo -u $real_user composer update",
		"sudo -u $real_user php artisan key:generate",
		"sudo -u $real_user cp ./templates/ReadMe.template.md README.md",
		"sudo -u $real_user php artisan vendor:publish --provider='CareSet\DURC\DURCServiceProvider'",
		"sudo -u $real_user php artisan vendor:publish --tag=laravel-handlebars",
		"chmod g+w storage/* -R", //this will actually be run as root!!
		];


	if(!file_exists('.env')){
		array_unshift($cmds,"sudo -u $real_user cp .env.example .env");	
	}else{
		echo "The .env file already exists, so we are not deleting it\n";
	}

	foreach($cmds as $this_command){
		echo "Running $this_command\n";
		system($this_command);
	}

/*
// for now, we are ignoring the installation of zermelo, because it requires the database to be configured
#these commands need to run as the regular user...
#create our local .env file, it is ignored by .gitignore and is where lots of good configirations live
sudo -u $real_user php artisan install:zermelo
sudo -u $real_user php artisan install:zermelobladetabular
sudo -u $real_user php artisan install:zermelobladecard
*/

