<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use tests\TagTestCase;

require_once('autoload.php');
require_once('vendor/autoload.php');

/**
 * Returns tags
 *
 * @param string $text
 * @return array
 */
function getTags(string $text): array
{
	$beginCloseTagString = '[/';
	$endTagString = ']';
	$beginTagString = '[';
	$descriptionTagString = ':';
	$tags = [];

	$closeTags = explode($beginCloseTagString, $text);

	foreach($closeTags as $closeTag) {
		$tagName = '';
		$tagValue = '';
		$tagDescription = '';

		$tagParts = explode($endTagString, $closeTag);
		$lastIndex = count($tagParts) - 1;

		if ($lastIndex > 0) {
			$tagValue = $tagParts[$lastIndex];
			$tagParts = explode($descriptionTagString, $tagParts[$lastIndex - 1]);
			$lastIndex = count($tagParts) - 1;

			if ($lastIndex > 0) {
				$tagDescription = $tagParts[$lastIndex];
				$tagParts = explode($beginTagString, $tagParts[$lastIndex - 1]);
				$lastIndex = count($tagParts) - 1;

				if ($lastIndex > 0) {
					$tagName = $tagParts[$lastIndex];
				}
			}
		}

		if ($tagName != '' && $tagDescription != '' && $tagValue != '') {
			$tags[] = [
				'key' => $tagName,
				'value' => [
					'description' => $tagDescription,
					'data' => $tagValue
				]
			];
		}
	}

	return $tags;
}

$test = new TagTestCase();

$testData = $test->provideTestData();

echo 'tests started.<br/>';

foreach ($testData as $testName => $testParams) {
	echo 'starts ' . $testName . '...<br/>';
	$test->testGet($testParams[0], $testParams[1]);
}

echo 'tests finished.';

?>