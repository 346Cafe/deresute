<?php

namespace towa0131\deresute{

	require_once "./vendor/autoload.php";

	use towa0131\deresute\DeresuteAPI;

	$api = new DeresuteAPI("12345678-9012-3456-7890-123456789012", 123456789, 123456789);
	$args = [
		"app_type" => 0,
		"campaign_data" => "",
		"campaign_sign" => md5("All your APIs are belong to us"),
		"campaign_user" => 1337
	];
	print_r($api->run($args, "/load/check"));
}
