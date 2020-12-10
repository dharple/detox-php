<?php
/**
 * Detox (https://github.com/dharple/detox/)
 *
 * @link      https://github.com/dharple/detox/
 * @copyright Copyright (c) 2017 Doug Harple
 * @license   https://github.com/dharple/detox/blob/master/LICENSE
 * @since     File available since Release 2.0.0
 */

namespace Outsanity\Detox\Tests\Filter;

use Outsanity\Detox\Filter\Uncgi;

/**
 * Tests the uncgi filter.
 *
 * @since      Class available since Release 2.0.0
 */
class UncgiTest 
	extends FilterTest
{

	/**
	 * Main set of tests
	 *
	 * @var array[]
	 */
	protected $tests = [
		[
			'input' => 'There%27s+nothing+to+see%2C+here%2C+so+move+on%21',
			'output' => "There's nothing to see, here, so move on!",
		],

		[
			'input' => '%3Chtml%3E%3Chead%3E%3Ctitle%3Esomething%2Chere%3C%2Ftitle%3E%3C%2Fhead%3E%3Cbody%3E%3Cp%3Efilename.txt.%3C%2Fp%3E%3C%2Fbody%3E%3C%2Fhtml%3E',
			'output' => '<html><head><title>something,here<_title><_head><body><p>filename.txt.<_p><_body><_html>',
		],

		[
			'input' => 'nothing',
			'output' => 'nothing',
		],

		[
			'input' => 'really%2Fbroken%00now',
			'output' => 'really_broken_now',
		],
	];

	/**
	 * Tests basic translation with a variety of encodings.
	 */
	public function testBasicTranslation()
	{
		$encodings = [ 'UTF-8', 'ISO-8859-1', 'ISO-8859-15', 'Windows-1252' ];

		$paths = [
			'',
			DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
			DIRECTORY_SEPARATOR . 'spacey namey' . DIRECTORY_SEPARATOR,
		];

		$filter = new Uncgi();

		foreach ($this->tests as $test) {
			foreach ($encodings as $encoding) {
				foreach ($paths as $path) {
					$original = mb_convert_encoding($path . $test['input'], $encoding, 'UTF-8');
					$expected = mb_convert_encoding($path . $test['output'], $encoding, 'UTF-8');

					$this->assertEquals(
						$expected,
						$filter->filter($original, $encoding),
						'Uncgi filter failed.  Encoding is set to: ' . $encoding . ', source is: ' . $test['input']
					);
				}
			}
		}
	}

	/**
	 * Tests basic translation with a variety of encodings.
	 */
	public function testWideTranslation()
	{
		$encodings = [ 'UCS-2', 'UCS-4' ];

		$paths = [
			'',
			DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
			DIRECTORY_SEPARATOR . 'spacey namey' . DIRECTORY_SEPARATOR,
		];

		$filter = new Uncgi();

		foreach ($this->tests as $test) {
			foreach ($encodings as $encoding) {
				foreach ($paths as $path) {
					$original = mb_convert_encoding($path . $test['input'], $encoding, 'UTF-8');
					$expected = mb_convert_encoding($path . $test['output'], $encoding, 'UTF-8');

					$this->assertEquals(
						$expected,
						$filter->filter($original, $encoding),
						'Uncgi filter failed.  Encoding is set to: ' . $encoding . ', source is: ' . $test['input']
					);
				}
			}
		}
	}


}
