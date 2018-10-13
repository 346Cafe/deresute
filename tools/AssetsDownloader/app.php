<?php

namespace towa0131\deresute\tools\AssetsDownloader{

	require_once "../../vendor/autoload.php";

	use towa0131\deresute\DeresuteAPI;
	use towa0131\deresute\ManifestDB;
	use towa0131\deresute\TestAPI;

	TestAPI::checkExtensions();

	$downloadDir = __DIR__ . "/dl/";

	$header = [
		"APP_VER: " . DeresuteAPI::APP_VER,
		"RES_VER: " . DeresuteAPI::RES_VER,
		"X-Unity-Version: " . DeresuteAPI::WC_VER,
		"User-Agent: Dalvik/1.6.0 (Linux; U; Android 4.4.2; SM-N9005 Build/NJH47F)",
		"Connection: keep-alive",
		"Host: " . ManifestDB::RESOURCE_SERVER
	];

	$downloader = new AssetsDownloader($downloadDir, $header);

	echo "Manifest Downloading..." . PHP_EOL;
	$downloader->downloadManifest(DeresuteAPI::RES_VER);

	echo "Downloading Assets..." . PHP_EOL;
	$downloader->downloadAssets();
}