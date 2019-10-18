<?php

namespace towa0131\deresute;

use towa0131\deresute\Cryptographer;

class DeresuteAPI{

	public const BASE_URL = "https://apis.game.starlight-stage.jp";

	public const RES_VER = 10061600;
	public const APP_VER = "5.2.5";
	public const WC_VER = "2018.3.8f1";

	public const VIEWER_ID_KEY = "s%5VNQ(H$&Bqb6#3+78h29!Ft4wSg)ex";
	public const SID_SALT = "r!I@nt8e5i=";

	protected $udid = "";
	protected $viewerId = 0;
	protected $userId = 0;

	protected $sid = "";

	public function __construct(string $udid, int $viewerId, int $userId){
		ini_set("msgpack.use_str8_serialization", 0); // Enable compatibility mode

		$this->udid = $udid;
		$this->viewerId = $viewerId;
		$this->userId = $userId;
		$this->sid = (string)$this->viewerId . (string)$this->udid;
	}

	/**
	 * Core API
	 */

	private function encrypt256(string $data = "", string $key, string $iv) : string{
		$padding = 16 - (strlen($data) % 16);
		$data .= str_repeat(chr($padding), $padding);
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
	}

	private function decrypt256(string $data = "", string $key, string $iv) : string{
		$data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
		$padding = ord($data[strlen($data) - 1]);
		return substr($data, 0, -$padding);
	}

	public function run(array $args, string $endpoint) : array{
		$vid_iv = mt_rand(10000000, 99999999) . mt_rand(10000000, 99999999);
		$args["timezone"] = "09:00:00";
		$args["viewer_id"] = $vid_iv . base64_encode($this->encrypt256((string)$this->viewerId, self::VIEWER_ID_KEY, $vid_iv));
		$plain = base64_encode(msgpack_pack($args));

		$key = mt_rand(1000000000, 9999999999) . mt_rand(1000000000, 9999999999) . mt_rand(100000000000, 999999999999);
		$msg_iv = hex2bin(str_replace("-", "", $this->udid));
		$body = base64_encode($this->encrypt256($plain, $key, $msg_iv) . $key);

		$headers = [
			"Host: apis.game.starlight-stage.jp",
			"User-Agent: Dalvik/2.1.0 (Linux; U; Android 8.1.0; Nexus 4 Build/XYZZ1Y)",
			"Content-Type: application/x-www-form-urlencoded",
			"Content-Length: " . strlen($body),
			"Connection: keep-alive",
			"Accept: */*",
			"Accept-Encoding: gzip, deflate",
			"Accept-Language: en-us",
			"X-Unity-Version: " . self::WC_VER,
			"UDID: " . Cryptographer::encode($this->udid),
			"USER-ID: " . Cryptographer::encode((string)$this->userId),
			"SID: " . md5($this->sid . self::SID_SALT),
			"PARAM: " . sha1($this->udid . (string)$this->viewerId . $endpoint . $plain),
			"DEVICE: 1",
			"APP-VER: " . self::APP_VER,
			"RES-VER: " . self::RES_VER,
			"DEVICE-ID: " . md5("Totally a real Android"),
			"DEVICE-NAME: Nexus 42",
			"GRAPHICS-DEVICE-NAME: 3dfx Voodoo2 (TM)",
			"IP-ADDRESS: 127.0.0.1",
			"PLATFORM-OS-VERSION: Android OS 13.3.7 / API-42 (XYZZ1Y/74726f6c6c)",
			"CARRIER: docomo",
			"KEYCHAIN: 727238026",
			"PROCESSOR-TYPE: ARMv7 VFPv3 NEON",
			"IDFA: "
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

		unset($curl);

		$response = base64_decode($response);
		$key = substr($response, -32, 32);
		$data = substr($response, 0, -32);

		$plain = $this->decrypt256($data, $key, $msg_iv);
		$result = msgpack_unpack(base64_decode($plain));

		if(isset($result["data_headers"]["sid"]) && !empty($result["data_headers"]["sid"])){
			$this->sid = $result["data_headers"]["sid"];
		}

		return $result;
	}

	public function getUdid() : string{
		return $this->udid;
	}

	public function getViewerId() : int{
		return $this->viewerId;
	}

	public function getUserId() : int{
		return $this->userId;
	}

	public static function generateHeader(string $host) : array{
		$header = [
			"APP-VER: " . self::APP_VER,
			"RES-VER: " . self::RES_VER,
			"X-Unity-Version: " . self::WC_VER,
			"User-Agent: Dalvik/1.6.0 (Linux; U; Android 4.4.2; SM-N9005 Build/NJH47F)",
			"Connection: keep-alive",
			"Host: " . $host
		];

		return $header;
	}

	/**
	 * Public API
	 */

	public function createNewAccount() : bool{
		$args = [
			"device_name" => "Nexus 42",
			"client_type" => "1",
			"os_version" => "Android OS 13.3.7 / API-42 (XYZZ1Y/74726f6c6c)",
			"app_version" => self::APP_VER,
			"resource_version" => "Android OS 13.3.7 / API-42 (XYZZ1Y/74726f6c6c)"
		];
		$result = $this->run($args, "/tool/signup");

		if($result["data_headers"]["result_code"] == 1){
			$this->viewerId = $result["data_headers"]["viewer_id"];
			$this->userId = $result["data_headers"]["user_id"];
			$this->sid = $result["data_headers"]["sid"];

			return true;
		}

		return false;
	}
}
