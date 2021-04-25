<?php

namespace towa0131\deresute;

class TestAPI
{
    public const TEST_ERROR = 0;
    public const TEST_OK = 1;
    public const TEST_WARNING = 2;

    public static function checkExtensions(bool $shutdown = true): int
    {
        $error = 0;
        $requireExt = ["unitylz4", "msgpack", "curl", "mbstring", "bcmath", "sqlite3", "cgss"];
        foreach ($requireExt as $extName) {
            if (!extension_loaded($extName)) {
                echo " No module loaded : " . $extName . PHP_EOL;
                $error++;
            }
        }

        if ($error > 0) {
            echo " " . $error . " error(s) occurred" . PHP_EOL;
            if ($shutdown) {
                exit(1);
            } else {
                return self::TEST_ERROR;
            }
        }

        return self::TEST_OK;
    }

    public static function checkAPI(bool $shutdown = false): int
    {
        $api = new DeresuteAPI("01234567-89ab-cdef-0123-456789abcdef", 123456789, 987654321);
        $args = [
            "app_type" => 0,
            "campaign_data" => "",
            "campaign_sign" => md5("All your APIs are belong to us"),
            "campaign_user" => 171780
        ];
        $result = $api->run($args, "/load/check");

        if (empty($result)) {
            return self::TEST_ERROR;
        }

        if ($result["data_headers"]["result_code"] === 1) {
            return self::TEST_OK;
        } else {
            return self::TEST_WARNING;
        }

        return self::TEST_ERROR;
    }
}
