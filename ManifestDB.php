<?php

namespace towa0131\deresute;

use towa0131\deresute\Cryptographer;

class ManifestDB{

	public const HTTP_SCHEME = "http://";
	public const RESOURCE_SERVER = "storage.game.starlight-stage.jp/";
    
	public const DB_NAME = "manifest.db";
	public const MANIFEST_TABLE = "manifests";
	public const DL_ROOT = "dl/";
	public const QUALITY_HIGH_DIR = "High/";
	public const QUALITY_LOW_DIR = "Low/";
	public const GENERIC_DIR = "Generic/";
	public const BLOB_DIR = "Blob/";
	public const MASTER_DIR = "Master/";
	public const COMMON_SOUND_DIR = "Common/";
	public const RESOURCES_DIR = "resources/";
	public const ASSETS_DIR = "AssetBundles/";
	public const SOUND_DIR = "Sound/";
	public const MOVIE_DIR = "Movie/";
	public const MANIFESTS_DIR = "manifests/";
	public const PLATFORM_ANDROID_DIR = "Android/";
	public const PLATFORM_IOS_DIR = "iOS/";
	public const PLATFORM_STANDALONE_DIR = "Standalone/";

	public const SOUND_SINGLE = 0;
	public const SOUND_BGM = 1;
	public const SOUND_LIVE = 2;
	public const SOUND_STORY = 3;
	public const SOUND_ROOM = 4;
	public const SOUND_VOICE = 5;
	public const SOUND_SE = 6;

	public static function getAssetBundleDirectory(){
		return self::getResourceServerURL() . self::DL_ROOT . self::RESOURCES_DIR . self::getQualityDirectory() . self::ASSETS_DIR . self::getPlatformDirectory();
	}

	public static function getManifestsDirectory(string $resVer){
		return self::getResourceServerURL() . self::DL_ROOT . $resVer . "/" . self::MANIFESTS_DIR;
	}

	public static function getSoundDirectory($type){
		$prefix = self::getResourceServerURL() . self::DL_ROOT . self::RESOURCES_DIR . self::getQualityDirectory() . self::SOUND_DIR;
		switch($type){
			case self::SOUND_SINGLE:
				return $prefix;
				break;

			case self::SOUND_BGM:
				return $prefix . self::COMMON_SOUND_DIR . "b/";
				break;

			case self::SOUND_STORY:
				return $prefix . self::COMMON_SOUND_DIR . "c/";
				break;

			case self::SOUND_LIVE:
				return $prefix . self::COMMON_SOUND_DIR . "l/";
				break;

			case self::SOUND_ROOM:
				return $prefix . self::COMMON_SOUND_DIR . "r/";
				break;

			case self::SOUND_VOICE:
				return $prefix . self::COMMON_SOUND_DIR . "v/";
				break;

			case self::SOUND_SE:
				return $prefix . self::COMMON_SOUND_DIR . "s/";
				break;

			default:
				return $prefix;
				break;

		}
	}

	public static function getMovieDirectory(string $resVer){
		return self::getResourceServerURL() . self::DL_ROOT . $resVer . "/" . self::getQualityDirectory() . self::MOVIE_DIR . self::getPlatformDirectory();
	}

	public static function getBlobDBDirectory(){
		return self::getResourceServerURL(). self::DL_ROOT . self::RESOURCES_DIR . self::GENERIC_DIR;
	}

	public static function getMasterDBDirectory(){
		return self::getResourceServerURL(). self::DL_ROOT . self::RESOURCES_DIR . self::GENERIC_DIR;
	}

	public static function getScheme(){
		return self::HTTP_SCHEME;
	}

	public static function getResourceServerURL(){
		return self::getScheme() . self::RESOURCE_SERVER;
	}

	public static function getQualityDirectory(int $type = 0){
		switch($type){
			case 0: // Quality: High
				return self::QUALITY_HIGH_DIR;
				break;
			case 1: // Quality: Low
				return self::QUALITY_LOW_DIR;
				break;
			default: // Quality: High
				return self::QUALITY_HIGH_DIR;
				break;
		}
	}

	public static function getPlatformDirectory(int $type = 0){
		switch($type){
			case 0: // Platform: Android
				return self::PLATFORM_ANDROID_DIR;
				break;
			case 1: // Platform: IOS
				return self::PLATFORM_IOS_DIR;
				break;
			case 2: //Platform: StandAlone
				return self::PLATFORM_STANDALONE_DIR;
				break;
			default: // Platform: Android
				return self::PLATFORM_ANDROID_DIR;
				break;
		}
	}
}