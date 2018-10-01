<?php

namespace towa0131\deresute{

	require_once "./vendor/autoload.php";

	use towa0131\deresute\DeresuteAPI;
	use towa0131\deresute\ManifestDB;

	echo "Hello!" . PHP_EOL;
	echo "Checking extensions..." . PHP_EOL;
	checkExtensions();

	echo "Testing API..." . PHP_EOL;
	$api = new DeresuteAPI("01234567-89ab-cdef-0123-456789abcdef", 123456789, 987654321);
	$args = [
		"app_type" => 0,
		"campaign_data" => "",
		"campaign_sign" => md5("All your APIs are belong to us"),
		"campaign_user" => 171780
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

	sleep(1);

	$downloadDir = __DIR__ . "/dl/";
	if(!file_exists($downloadDir)){
		$result = mkdir($downloadDir, 0777);
		if(!$result){
			echo "Failed create dl directory";
			exit(1);
		}
	}

	echo "Manifest Downloading..." . PHP_EOL;
	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_HEADER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "gzip",
		CURLOPT_TIMEOUT => 30
	]);
	$response = curl_exec($curl);
	$info = curl_getinfo($curl);

	curl_close($curl);

	if($info["http_code"] === 200){
		echo "Successfully download manifest" . PHP_EOL;
		file_put_contents($downloadDir . "manifest_" . DeresuteAPI::RES_VER, $response);
		echo "Decompressing..." . PHP_EOL;
		$buffer = unity_lz4_uncompress($response);
		file_put_contents($downloadDir . "manifest.db", $buffer);
		echo "Successful!" . PHP_EOL;
	}else{
		echo "Error! " . $info["http_code"] . PHP_EOL;
	}

	echo "Bye!" . PHP_EOL;

	function checkExtensions(){
		$error = 0;
		$requireExt = ["unitylz4", "msgpack", "curl", "mbstring"];
		foreach($requireExt as $extName){
			if(!extension_loaded($extName)){
				echo " No module loaded : " . $extName . PHP_EOL;
				$error++;
			}
		}

		if($error > 0){
			echo " " . $error . " error(s) occurred" . PHP_EOL;
			exit(1);
		}
	}
}
