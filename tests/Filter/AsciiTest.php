<?php
/**
 * Detox (https://github.com/dharple/detox/)
 *
 * @link      https://github.com/dharple/detox/
 * @copyright Copyright (c) 2017 Doug Harple
 * @license   https://github.com/dharple/detox/blob/master/LICENSE
 * @since     File available since Release 2.0.0
 */

namespace DetoxTest\Filter;

use Detox\Filter\Ascii;

/**
 * Tests the safe filter.
 *
 * @since      Class available since Release 2.0.0
 */
class AsciiTest 
	extends FilterTest
{

	/**
	 * Tests basic transliteration with a variety of encodings.
	 */
	public function testBasicTransliteration()
	{
		$encodings = [ 'UTF-8' ];

		$paths = [
			'',
			DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
			DIRECTORY_SEPARATOR . 'spacey namey' . DIRECTORY_SEPARATOR,
		];

		$tests[] = [
			'input' => 'Sláinte',
			'output' => 'Slainte'
		];

		//
		// the next few are adapted from
		// https://www.w3.org/2001/06/utf-8-test/UTF-8-demo.html
		//

		$tests[] = [
			'input' => 'Anführungszeichen',
			'output' => 'Anfuhrungszeichen'
		];

		$tests[] = [
			'input' => '“We’ve been here”',
			'output' => '"We\'ve been here"'
		];

		$tests[] = [
			'input' => '14.95 €',
			'output' => '14.95 EUR'
		];

		$tests[] = [
			'input' => '1lI|, 0OD, 8B ',
			'output' => '1lI|, 0OD, 8B '
		];

		$tests[] = [
			'input' => '†, ‡, ‰, •, 3–4, —, −5, +5, ™, …',
			'output' => '+, ?, ?, o, 3-4, --, -5, +5, (TM), ...'
		];

		$filter = new Ascii();

		foreach ($tests as $test) {
			foreach ($encodings as $encoding) {
				foreach ($paths as $path) {
					foreach (array(true, false) as $byCharacter) {
						$filter->setConvertByCharacter($byCharacter);
						$this->assertEquals(
							$path . $test['output'],
							$filter->filter($path . $test['input'], $encoding),
							'Ascii filter failed.  Encoding is set to: ' . $encoding
						);
					}
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

		$tests = [];

		$tests[] = [
			'input' => 'Sláinte',
			'output' => 'Slainte'
		];

		//
		// the next few are adapted from
		// https://www.w3.org/2001/06/utf-8-test/UTF-8-demo.html
		//

		$tests[] = [
			'input' => 'Anführungszeichen',
			'output' => 'Anfuhrungszeichen'
		];

		$tests[] = [
			'input' => '“We’ve been here”',
			'output' => '"We\'ve been here"'
		];

		$tests[] = [
			'input' => '14.95 €',
			'output' => '14.95 EUR'
		];

		$tests[] = [
			'input' => '1lI|, 0OD, 8B ',
			'output' => '1lI|, 0OD, 8B '
		];

		$tests[] = [
			'input' => '†, ‡, ‰, •, 3–4, —, −5, +5, ™, …',
			'output' => '+, ?, ?, o, 3-4, --, -5, +5, (TM), ...'
		];

		$filter = new Ascii();

		foreach ($tests as $test) {
			foreach ($encodings as $encoding) {
				foreach ($paths as $path) {
					foreach (array(true, false) as $byCharacter) {
						$filter->setConvertByCharacter($byCharacter);

						$original = mb_convert_encoding($path . $test['input'], $encoding, 'UTF-8');
						$expected = mb_convert_encoding($path . $test['output'], $encoding, 'UTF-8');

						$this->assertEquals(
							$expected,
							$filter->filter($original, $encoding),
							'Ascii filter failed.  Encoding is set to: ' . $encoding . ', source is: ' . $test['input']
						);
					}
				}
			}
		}
	}


}
