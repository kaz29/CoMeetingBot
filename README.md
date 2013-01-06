#CoMeetingBot

Supported Services

* github WebHook
* Jenkins Job Notifications

##Installation

	$ cd your_app_path
	$ git clone git://github.com/kaz29/CoMeetingBot.git
	$ cd CoMeetingBot
	$ cp lib/config.sample.php lib/config.php
	 
Set your co-meeting account informations to lib/config.php

	<?php
	/*
	 *    copy this file to config.php and set your co-meeting account information.
	 */
	$params = array(
    	'email' => 'your email address',
    	'password' => 'your password',
	);

Set DocumentRoot to CoMeetingBot/app .

## Endpoint URL

* github WebHook endpoint url

	http(s)://your hostname/github/[co-meeting meeting id]/
	
* Jenkins Job Notifications - Notification Endpoints

	http(s)://your hostname/jenkins/[co-meeting meeting id]/
