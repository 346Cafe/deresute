<?php

namespace towa0131\deresute\tools\AssetsDownloader;

use ByteUnits\Metric;

class ProgressBar {

	public const FORMAT_CURL = 0;

	public static function directPrint(int $current, int $max, int $format, ...$additionalData) {
		if ($current <= 0 || $max <= 0) {
			return false;
		}

		$percent = floor($current / $max * 100);

		switch($format){
			case self::FORMAT_CURL:
				$currentMB = Metric::bytes($current)->format("mB/000");
				$maxMB = Metric::bytes($max)->format("mB/000");
				$bar = str_repeat("|", floor($percent / 2));

				$format = sprintf(" %3s%% [%-50s] %s/%s", $percent, $bar, $currentMB, $maxMB);

				echo $format;
				echo str_repeat("\x08", strlen($format));
				break;
		}

		return true;
	}

}
