<?php
	//this is the script that sets up Eden...

//TODO This script should check for a properly setup zermelo, and user auth system before running the installation...
// so that we can avoid the stage where we get errors because _zermelo_cache/_zermelo_config are not there
// and where JWT security does not enable because we cannot save users...
//also the storage directory needs to be writeable by the webserver in a predictably manner... 


	$composer_locations = [
		'/bin/composer',
		'/usr/bin/composer',
		'/usr/local/bin/composer',
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
		echo "Follow these instructions https://getcomposer.org/doc/00-intro.md#globally and install it to /usr/bin/composer\n";
		echo "Since the default of /usr/local/bin/composer is not a ubuntu default\n";
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

	echo "Checking for $composer_config_dir\n";	
	if(!file_exists($composer_config_dir)){
		echo "Error: The directory $composer_config_dir needs to exist and be writable to save authorizations etc... \n";
		exit();
	}



	$cmds = [
		"sudo chown $real_user:$real_user $composer_config_dir -R",
		"sudo -u $real_user composer update",
		"sudo -u $real_user php artisan key:generate",
		"sudo -u $real_user cp ./templates/ReadMe.template.md README.md",
		"sudo -u $real_user php artisan vendor:publish --provider='CareSet\DURC\DURCServiceProvider'",
		"sudo -u $real_user php artisan vendor:publish --tag=laravel-handlebars",
		"chmod g+w storage/* -R", //this will actually be run as root!! and 
		"chown $real_user:www-data storage/* -R", //this will 
		"usermod -a -G www-data $real_user",
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


echo "You need to run the zermelo installation now... since it is interactive\n ./artisan zermelo:install\n\n";
echo "Before you do that, you need to create a database user for this instance and edit .env to give database access\n";
echo "be sure to give appropriate access to the auth, _zermelo_config, and _zermelo_cache databases, as well as a new database for this server\n";

/*
// for now, we are ignoring the installation of zermelo, because it requires the database to be configured
#these commands need to run as the regular user...
#create our local .env file, it is ignored by .gitignore and is where lots of good configirations live
sudo -u $real_user php artisan install:zermelo
sudo -u $real_user php artisan install:zermelobladetabular
sudo -u $real_user php artisan install:zermelobladecard
*/

