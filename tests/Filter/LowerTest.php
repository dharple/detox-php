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

use Outsanity\Detox\Filter\Lower;

/**
 * Tests the lowercase filter.
 *
 * @since      Class available since Release 2.0.0
 */
class LowerTest 
	extends FilterTest
{

	/**
	 * Main set of tests
	 *
	 * @var array[]
	 */
	protected $tests = [
		[
			'input' => 'Sláinte',
			'output' => 'sláinte'
		],

		[
			'input' => 'ÜBER',
			'output' => 'über',
		],

		[
			'input' => 'Neque Porro Quisquam Est Qui Dolorem Ipsum Quia Dolor Sit Amet, Consectetur, Adipisci Velit...',
			'output' => 'neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...'
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

		$filter = new Lower();

		foreach ($this->tests as $test) {
			foreach ($encodings as $encoding) {
				foreach ($paths as $path) {
					$original = mb_convert_encoding($path . $test['input'], $encoding, 'UTF-8');
					$expected = mb_convert_encoding($path . $test['output'], $encoding, 'UTF-8');

					$this->assertEquals(
						$expected,
						$filter->filter($original, $encoding),
						'Lower filter failed.  Encoding is set to: ' . $encoding . ', source is: ' . $test['input']
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

		$filter = new Lower();

		foreach ($this->tests as $test) {
			foreach ($encodings as $encoding) {
				foreach ($paths as $path) {
					$original = mb_convert_encoding($path . $test['input'], $encoding, 'UTF-8');
					$expected = mb_convert_encoding($path . $test['output'], $encoding, 'UTF-8');

					$this->assertEquals(
						$expected,
						$filter->filter($original, $encoding),
						'Lower filter failed.  Encoding is set to: ' . $encoding . ', source is: ' . $test['input']
					);
				}
			}
		}
	}


}
