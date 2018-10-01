<?php

namespace towa0131\deresute;

use towa0131\deresute\Cryptographer;

class DeresuteAPI{

	public const BASE_URL = "https://apis.game.starlight-stage.jp";

	public const RES_VER = 10045300;
	public const APP_VER = "4.2.1";
	public const WC_VER = "2017.4.2f2";

	public const VIEWER_ID_KEY = "s%5VNQ(H$&Bqb6#3+78h29!Ft4wSg)ex";
	public const SID_SALT = "r!I@nt8e5i=";

	protected $udid = "";
	protected $viewerId = 0;
	protected $userId = 0;
	protected $sid = "";

	public function __construct(string $udid, int $viewerId, int $userId){
		$this->udid = $udid;
		$this->viewerId = $viewerId;
		$this->userId = $userId;
		$this->sid = (string)$this->viewerId . (string)$this->udid;
	}

	public function encrypt256($data = "", $key, $iv) {
		$key = str_pad($key, 32, "\0");
		$padding = 32 - (strlen($data) % 32);
		$data .= str_repeat(chr(0), $padding);
		$encrypted = phpseclib_mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_CBC, $iv);
		return rtrim($encrypted);
	}

	public function decrypt256($data = "", $key, $iv) {
		return trim(phpseclib_mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_CBC, $iv), "\0");
	}

	public function run(array $args, string $endpoint){
		$vid_iv = mt_rand(1000000000, 9999999999) . mt_rand(1000000000, 9999999999) . mt_rand(100000000000, 999999999999);
		$args["timezone"] = "09:00:00";
		$args["viewer_id"] = $vid_iv . base64_encode($this->encrypt256((string)$this->viewerId, self::VIEWER_ID_KEY, $vid_iv));
		$plain = base64_encode(msgpack_pack($args));

		$key = mt_rand(1000000000, 9999999999) . mt_rand(1000000000, 9999999999) . mt_rand(100000000000, 999999999999);
		$msg_iv = str_replace("-", "", $this->udid);
		$body = base64_encode($this->encrypt256($plain, $key, $msg_iv) . $key);

		$headers = [
			"PARAM: " . sha1($this->udid . (string)$this->viewerId . $endpoint . $plain),
			"KEYCHAIN: 727238026",
			"CARRIER: google",
			"UDID: " . Cryptographer::encode($this->udid),
			"USER_ID: " . Cryptographer::encode((string)$this->userId),
			"SID: " . md5($this->sid . self::SID_SALT),
			"APP_VER: " . self::APP_VER,
			"RES_VER: " . self::RES_VER,
			"X-Unity-Version: " . self::WC_VER,
			"IP_ADDRESS: 127.0.0.1",
			"DEVICE_NAME: Nexus 42",
			"GRAPHICS_DEVICE_NAME: 3dfx Voodoo2 (TM)",
			"DEVICE_ID: " . md5("Totally a real Android"),
			"PLATFORM_OS_VERSION: Android OS 13.3.7 / API-42 (XYZZ1Y/74726f6c6c)",
			"DEVICE: 1",
			"Content-Type: application/x-www-form-urlencoded",
			"Content-Length: " . strlen($body),
			"User-Agent: Dalvik/1.6.0 (Linux; U; Android 4.4.2; SM-N9005 Build/NJH47F)",
			"Connection: keep-alive",
			"Host: apis.game.starlight-stage.jp"
		];

		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => self::BASE_URL . $endpoint,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $body,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "gzip, deflate"
		]);
		$response = curl_exec($curl);

		curl_close($curl);

		$response = base64_decode($response);
		$key = substr($response, -32, 32);
		//echo "[1]" . $key  . PHP_EOL . "[2]" . $response . PHP_EOL;
		$plain = $this->decrypt256(substr($response, 0, -32), $key, $msg_iv);
		$result = msgpack_unpack(base64_decode($plain));

		return $result;
	}
}