<?php

namespace towa0131\deresute\tools\AssetsDownloader
{
	require_once __DIR__ . "/../../vendor/autoload.php";

	use towa0131\deresute\DeresuteAPI;
	use towa0131\deresute\ManifestDB;
	use towa0131\deresute\TestAPI;

	TestAPI::checkExtensions();

	$downloadDir = __DIR__ . "/dl/";

	$header = DeresuteAPI::generateHeader(ManifestDB::RESOURCE_SERVER);

	$downloader = new AssetsDownloader($downloadDir, $header);

	echo "Manifest Downloading..." . PHP_EOL;
	$downloader->downloadManifest(DeresuteAPI::RES_VER);

	echo "Master Downloading..." . PHP_EOL;
	$downloader->downloadMaster();

	echo "Downloading Assets..." . PHP_EOL;
	$downloader->downloadAssets();

	echo "Extracting Assets..." . PHP_EOL;
	$downloader->extractAssets();
}
