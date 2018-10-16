<?php

namespace towa0131\deresute;

class Cryptographer{

	public static function encode(string $data){
		$str = "";
		for($i = 0; $i < mb_strlen($data); $i++){
			$str .= mt_rand(0, 9) . mt_rand(0, 9) . chr(ord($data[$i]) + 10) . mt_rand(0, 9);
		}
		return sprintf("%04x", strlen($data)) . $str .
				mt_rand(1000000000, 9999999999) . mt_rand(1000000000, 9999999999) . mt_rand(100000000000, 999999999999);
	}

	public static function decode(string $data){
		$num = hexdec(substr($data, 0, 4));
		$result = '';
		for ($i = 6; $i < strlen($data) && strlen($result) < $num; $i += 4) {
			$result .= chr(ord($data[$i]) - 10);
		}
		return $result;
	}
}