<?php

namespace towa0131\deresute\tools\AssetsDownloader;

use towa0131\deresute\DeresuteAPI;
use towa0131\deresute\ManifestDB;

class AssetsDownloader{

	private $path = __DIR__ . "/dl/";
	private $header = [];
	private $mode = 0777;

	private $db;

	public function __construct(string $path = __DIR__ . "/dl/", array $header, int $mode = 0777, \SQLite3 $db = null){
		$this->path = $path;
		$this->header = $header;
		$this->mode = $mode;
		$this->db = $db;

		$this->createDirectory();
	}

	public function downloadManifest(int $resver){
		$url = ManifestDB::getManifestsDirectory($resver) . "Android_AHigh_SHigh";
		$this->getContents($url, $response, $info);

		if($info["http_code"] === 200){
			echo PHP_EOL;
			echo "Successfully download manifest" . PHP_EOL;
			file_put_contents($this->path . "manifest_" . $resver, $response);

			echo "Decompressing..." . PHP_EOL;
			$buffer = unity_lz4_uncompress($response);
			file_put_contents($this->path . "manifest.db", $buffer);

			$this->db = new \SQLite3($this->path . "manifest.db");

			echo "Successful!" . PHP_EOL;
		}else{
			echo "Error! HttpCode : " . $info["http_code"] . PHP_EOL;
			return false;
		}

		return true;
	}

	public function downloadAssets(\SQLite3 $db = null){
		if($this->db == null){
			if($db == null){
				return false;
			}
			$this->db = $db;
		}
			
		$pathEntry = ["sounds/", "sounds/bgm/", "sounds/live/"];
		foreach($pathEntry as $entry){
			if(!file_exists($this->path . $entry)){
				$result = mkdir($this->path . $entry, 0777);
				if(!$result){
					echo "Failed to create " . $this->path . $entry . "directory";
					exit(1);
				}
			}
		}

		echo "Downloading BGM..." . PHP_EOL;

		$this->downloadSounds(ManifestDB::SOUND_BGM);

		echo "Downloading live sounds..." . PHP_EOL;
	
		$this->downloadSounds(ManifestDB::SOUND_LIVE);

		echo "Successful!" . PHP_EOL;
	}

	public function downloadSounds(int $type = ManifestDB::SOUND_BGM){
		switch($type){
			case ManifestDB::SOUND_BGM:
				$result = $this->db->query("SELECT '" . ManifestDB::getSoundDirectory(ManifestDB::SOUND_BGM) . "'||hash AS url, REPLACE(REPLACE(name,'b/',''),'.acb','') AS filename FROM manifests WHERE name LIKE 'b/%acb'");
				$dir = "sounds/bgm/";
				break;

			case ManifestDB::SOUND_LIVE:
				$result = $this->db->query("SELECT '" . ManifestDB::getSoundDirectory(ManifestDB::SOUND_LIVE) . "'||hash AS url, REPLACE(REPLACE(name,'l/',''),'.acb','') AS filename FROM manifests WHERE name LIKE 'l/%acb'");
				$dir = "sounds/live/";
				break;
			default:
				return false;
		}

		while($row = $result->fetchArray()){
			$url = $row[0];
			$name = $row[1];

			echo "Downloading : " . $name . PHP_EOL;
			$this->getContents($url, $response, $info, "progressB");

			echo PHP_EOL;

			file_put_contents($this->path . $dir . $name . ".acb", $response);
		}

		return true;
	}

	private function createDirectory(){
		if(!file_exists($this->path)){
			$result = mkdir($this->path, 0777);
			if(!$result){
				echo "Failed to create " . $this->path . " directory";
				exit(1);
			}
		}
	}

	private function getContents(string $url, &$response, &$info, string $progress = "progressA", int $timeout = 30){
		$curl = curl_init();
		curl_setopt_array($curl, [
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_HTTPHEADER => $this->header,
		CURLOPT_HEADER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "gzip",
		CURLOPT_TIMEOUT => $timeout,
		CURLOPT_NOPROGRESS => false,
		CURLOPT_PROGRESSFUNCTION => function($curl, $downloadSize, $downloaded, $uploadSize, $uploaded) use ($progress){
			$this->$progress($curl, $downloadSize, $downloaded, $uploadSize, $uploaded);
		}
		]);
		$response = curl_exec($curl);
		$info = curl_getinfo($curl);

		curl_close($curl);
	}

	private function progressA($curl, int $downloadSize, int $downloaded, int $uploadSize, int $uploaded){
		if($downloadSize <= 0){
			return false;
		}

		$info = curl_getinfo($curl);
		if($info["http_code"] !== 200){
			echo "Error! HttpCode : " . $info["http_code"] . PHP_EOL;
			countinue;
		}

		ProgressBar::directPrint($downloaded, $downloadSize, ProgressBar::FORMAT_CURL);
		usleep(500);

		return true;
	}

	private function progressB($curl, int $downloadSize, int $downloaded, int $uploadSize, int $uploaded){
		if($downloadSize <= 0){
			return false;
		}

		ProgressBar::directPrint($downloaded, $downloadSize, ProgressBar::FORMAT_CURL);

		return true;
	}
}