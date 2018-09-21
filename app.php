<?php

namespace towa0131\deresute{

	require_once "./vendor/autoload.php";

	use towa0131\deresute\DeresuteAPI;
	use towa0131\deresute\ManifestDB;

	echo "Hello!" . PHP_EOL;
	echo "Testing API..." . PHP_EOL;
	$api = new DeresuteAPI("12345678-9012-3456-7890-123456789012", 123456789, 123456789);
	$args = [
		"app_type" => 0,
		"campaign_data" => "",
		"campaign_sign" => md5("All your APIs are belong to us"),
		"campaign_user" => 1337
	];
	print_r($api->run($args, "/load/check"));

	$url = ManifestDB::getManifestsDirectory(DeresuteAPI::RES_VER) . "Android_AHigh_SHigh";
	$headers = [
		"APP_VER: " . DeresuteAPI::APP_VER,
		"RES_VER: " . DeresuteAPI::RES_VER,
		"X-Unity-Version: " . DeresuteAPI::WC_VER,
		"User-Agent: Dalvik/1.6.0 (Linux; U; Android 4.4.2; SM-N9005 Build/NJH47F)",
		"Connection: keep-alive",
		"Host: " . ManifestDB::RESOURCE_SERVER
	];

	echo "Manifest Downloading..." . PHP_EOL;
	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_HEADER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "gzip"
	]);
	$response = curl_exec($curl);
	$info = curl_getinfo($curl);
	curl_close($curl);

	if($info["http_code"] === 200){
		echo "Successfully download manifest" . PHP_EOL;
		file_put_contents("manifest_" . DeresuteAPI::RES_VER, $response);
	}else{
		echo "Error!" . PHP_EOL;
	}

	echo "Bye!" . PHP_EOL;
}
