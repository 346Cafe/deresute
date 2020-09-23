<?php

namespace towa0131\deresute;

class Cryptographer
{
	public static function encode(string $data): string
	{
		$str = "";
		for ($i = 0; $i < mb_strlen($data); $i++) {
			$str .= mt_rand(0, 9) . mt_rand(0, 9) . chr(ord($data[$i]) + 10) . mt_rand(0, 9);
		}

		return sprintf("%04x", strlen($data)) . $str .
				mt_rand(10000000, 99999999) . mt_rand(10000000, 99999999);
	}

	public static function decode(string $data): string
	{
		$num = hexdec(substr($data, 0, 4));
		$result = "";
		for ($i = 6; $i < strlen($data) && strlen($result) < $num; $i += 4) {
			$result .= chr(ord($data[$i]) - 10);
		}

		return $result;
	}
}
