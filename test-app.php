<?php

namespace towa0131\deresute{

	require_once "./vendor/autoload.php";

	use towa0131\deresute\TestAPI;

	$test = new TestAPI();

	echo "Testing..." . PHP_EOL;

	$time = time();

	$tests = 0;
	$successful = 0;
	$failed = 0;
	$warning = 0;

	$result = $test->checkExtensions(false);
	$tests++;

	getResult($result, $successful, $failed, $warning);

	$result = $test->checkAPI(false);
	$tests++;

	getResult($result, $successful, $failed, $warning);

	$duration = time() - $time;
	$time = sprintf("%02d:%02d:%02d", floor($duration / 3600), floor(($duration / 60) % 60), ($duration % 60));

	$titleFormat = str_repeat("=", 80) . PHP_EOL .
	" %s " . PHP_EOL .
	str_repeat("-", 80) . PHP_EOL;

	$format = " %-20s :   %s" . PHP_EOL;

	echo sprintf($titleFormat, "TEST RESULT");
	echo sprintf($format, "Number of tests", $tests);
	echo sprintf($format, "Tests successful", $successful);
	echo sprintf($format, "Tests failed", $failed);
	echo sprintf($format, "Tests warning", $warning);

	echo sprintf($titleFormat, "TIME RESULT");
	echo sprintf($format, "Time taken", $time);

	function getResult($result, &$successful, &$failed, &$warning){
		switch($result){
			case TestAPI::TEST_ERROR:
				$failed++;
				break;

			case TestAPI::TEST_OK:
				$successful++;
				break;

			case TestAPI::TEST_WARNING:
				$warning++;
				break;
		}
	}
}