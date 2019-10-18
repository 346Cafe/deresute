<?php

namespace towa0131\deresute\tools\AssetsDownloader;

use towa0131\deresute\DeresuteAPI;
use towa0131\deresute\ManifestDB;

use ByteUnits\Metric;

class AssetsDownloader{

	private $path = __DIR__ . "/dl/";
	private $header = [];
	private $mode = 0777;

	private $contentsCount = 0;
	private $totalBytes = 0;

	public function __construct(string $path = __DIR__ . "/dl/", array $header, int $mode = 0777){
		$this->path = $path;
		$this->header = $header;
		$this->mode = $mode;

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

			\ORM::configure("sqlite:" . $this->path . "manifest.db");

			echo "Successful!" . PHP_EOL;
		}else{
			echo "Error! HttpCode : " . $info["http_code"] . PHP_EOL;
			return false;
		}

		return true;
	}

	public function downloadMaster(){
		$this->checkDB();

		$result = \ORM::for_table("manifests")->where("name", "master.mdb")->find_one();
		$name = $result->name;
		$hash = $result->hash;
		$url = ManifestDB::getMasterDBDirectory() . substr($hash, 0, 2) . "/" . $hash;
		$this->getContents($url, $response, $info);

		if($info["http_code"] === 200){
			echo PHP_EOL;
			echo "Successfully download master" . PHP_EOL;
			file_put_contents($this->path . "master.mdb", $response);

			echo "Decompressing..." . PHP_EOL;
			$buffer = unity_lz4_uncompress($response);
			file_put_contents($this->path . "master.db", $buffer);

			echo "Successful!" . PHP_EOL;
		}else{
			echo "Error! HttpCode : " . $info["http_code"] . PHP_EOL;
			return false;
		}

		return true;
	}

	public function downloadAssets(){
		$this->checkDB();

		$currentTimer = new Timer(time());
		$totalTimer = new Timer(time());

		$pathEntry = ["sounds/", "sounds/bgm/", "sounds/live/", "sounds/story/", "sounds/room/", "sounds/voice/", "sounds/se/", "assetbundle/"];
		foreach($pathEntry as $entry){
			if(!file_exists($this->path . $entry)){
				$result = mkdir($this->path . $entry, $this->mode);
				if(!$result){
					echo "Failed to create " . $this->path . $entry . "directory";
					exit(1);
				}
			}
		}

		$time = function() use (&$currentTimer, &$totalTimer){
			$time = time();
			$diff = $currentTimer->diff($time);
			$currentTimer->set($time);
			$total = $totalTimer->diff($time);

			$format = "%02d:%02d:%02d";
			$diff = sprintf($format, floor($diff / 3600), floor(($diff / 60) % 60), ($diff % 60));
			$total = sprintf($format, floor($total / 3600), floor(($total / 60) % 60), ($total % 60));

			return [$diff, $total];
		};

		$format = str_repeat("=", 80) . PHP_EOL .
		" %-30s | Time Taken : %s / Total Time : %s" . PHP_EOL .
		str_repeat("-", 80) . PHP_EOL;

		$summary = str_repeat("=", 80) . PHP_EOL .
		" %-20s" . PHP_EOL .
		str_repeat("-", 80) . PHP_EOL .
		" Total Time : %s" . PHP_EOL .
		" Total Files : %d" . PHP_EOL .
		" Total File Size : %s" . PHP_EOL .
		str_repeat("=", 80) . PHP_EOL;

		$download = function(string $prefix, int $type) use ($format, $time){
			$result = $time();
			echo sprintf($format, sprintf("Downloading %s sonuds...", $prefix), $result[0], $result[1]);
			sleep(3);
			$this->downloadSounds($type);
		};

		$download("bgm", ManifestDB::SOUND_BGM);
		$download("live", ManifestDB::SOUND_LIVE);
		$download("story", ManifestDB::SOUND_STORY);
		$download("room", ManifestDB::SOUND_ROOM);
		$download("voice", ManifestDB::SOUND_VOICE);
		$download("se", ManifestDB::SOUND_SE);

		$result = $time();
		echo sprintf($format, "Downloading AssetBundle...", $result[0], $result[1]);
		sleep(3);
		$this->downloadAssetBundle();

		$result = $time();
		echo sprintf($summary, "SUMMARY OF RESULTS", $result[1], $this->contentsCount, Metric::bytes($this->totalBytes)->format("GB/000"));
		echo "Successful!" . PHP_EOL;
	}

	public function extractAssets(){
		foreach(glob("dl/sounds/*/*.acb", GLOB_BRACE) as $file){
			if(is_file($file)){
				$exploded = explode("/", $file);
				$name = str_replace(".acb", "", end($exploded));
				$path = str_replace($name . ".acb", "", $file);
				echo "Extracting : " . $name . PHP_EOL;
				acbunpack($file);
				hca2wav($path . "_acb_" . $name . "/" . $name . ".hca", "dl/" . $name . ".wav", CGSS_HCA_KEY_1, CGSS_HCA_KEY_2);
			}
		}
	}

	private function downloadSounds(int $type = ManifestDB::SOUND_BGM){
		switch($type){
			case ManifestDB::SOUND_BGM:
				$index = "b/";
				$dir = "sounds/bgm/";
				break;

			case ManifestDB::SOUND_LIVE:
				$index = "l/";
				$dir = "sounds/live/";
				break;

			case ManifestDB::SOUND_STORY:
				$index = "c/";
				$dir = "sounds/story/";
				break;

			case ManifestDB::SOUND_ROOM:
				$index = "r/";
				$dir = "sounds/room/";
				break;

			case ManifestDB::SOUND_VOICE:
				$index = "v/";
				$dir = "sounds/voice/";
				break;

			case ManifestDB::SOUND_SE:
				$index = "s/";
				$dir = "sounds/se/";
				break;

			default:
				return false;
		}

		$results = \ORM::for_table("manifests")->whereLike("name", $index . "%acb")->find_many();

		foreach($results as $result){
			$name = str_replace($index, "", $result->name);
			$url = ManifestDB::getSoundDirectory() . substr($result->hash, 0, 2) . "/" . $result->hash;

			echo "Downloading : " . $name . PHP_EOL;
			$this->getContents($url, $response, $info, "progressB");

			echo PHP_EOL;

			file_put_contents($this->path . $dir . $name, $response);
		}

		return true;
	}

	private function downloadAssetBundle(){
		$results = \ORM::for_table("manifests")->whereLike("name", "%unity3d")->find_many();

		foreach($results as $result){
			if(file_exists($this->path . "assetbundle/" . $result->name)){
				echo "Passed : " . $result->name . PHP_EOL;
				continue;
			}

			$url = ManifestDB::getAssetBundleDirectory() . substr($result->hash, 0, 2) . "/" . $result->hash;

			echo "Downloading : " . $result->name . PHP_EOL;
			$this->getContents($url, $response, $info, "progressB");
			$buffer = unity_lz4_uncompress($response);

			echo PHP_EOL;

			file_put_contents($this->path . "assetbundle/" . $result->name, $buffer);
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

	private function checkDB(bool $shutdown = true){
		try{
			\ORM::for_table("manifests")->find_many();
		}catch(\PDOException $exception){
			if($shutdown){
				echo "Error! DB was not found" . PHP_EOL;
				exit(1);
			}

			return false;
		}
		return true;
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

		$this->contentsCount++;
		$this->totalBytes += $info["download_content_length"];

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
