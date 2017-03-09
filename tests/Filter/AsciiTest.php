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
 * Tests the ASCII filter.
 *
 * @since      Class available since Release 2.0.0
 */
class AsciiTest 
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
			'output' => 'Slainte'
		],

		//
		// the next few are adapted from
		// https://www.w3.org/2001/06/utf-8-test/UTF-8-demo.html
		//

		[
			'input' => 'Anführungszeichen',
			'output' => 'Anfuhrungszeichen'
		],

		[
			'input' => '“We’ve been here”',
			'output' => '"We\'ve been here"'
		],

		[
			'input' => '14.95 €',
			'output' => '14.95 EUR'
		],

		//
		// the following should start to fail as iconv updates its
		// transliteration table, or we do
		//
		// adapted from: https://www.w3.org/2001/06/utf-8-test/UTF-8-demo.html
		//

		[
			'input' => '†, ‡, ‰, •, 3–4, —, −5, +5, ™, …',
			'output' => '+, ?, ?, o, 3-4, --, -5, +5, (TM), ...'
		],

		[
			'input' => 'Hello world, Καλημέρα κόσμε, コンニチハ',
			'output' => 'Hello world, ????u??? ???u?, ?????',
			'minimumIconvVersion' => '2.23',
		],

		[
			'input' => '⡌⠁⠧⠑ ⠼⠁⠒  ⡍⠜⠇⠑⠹⠰⠎ ⡣⠕⠌',
			'output' => '???? ???  ??????? ???'
		],

		[
			'input' => 'ði ıntəˈnæʃənəl fəˈnɛtık əsoʊsiˈeıʃn',
			'output' => 'di int?\'nae??n?l f?\'netik ?so?si\'ei?n',
			'minimumIconvVersion' => '2.23',
		],

	];

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

		$filter = new Ascii();

		foreach ($this->tests as $test) {
			if (isset($test['minimumIconvVersion']) && version_compare($test['minimumIconvVersion'], ICONV_VERSION, '>')) {
				$this->markTestIncomplete('Some strings require iconv > ' . $test['minimumIconvVersion'] . '; system version is: '. ICONV_VERSION);
				continue;
			}
			foreach ($encodings as $encoding) {
				foreach ($paths as $path) {
					foreach ([true, false] as $byCharacter) {
						$filter->setConvertByCharacter($byCharacter);

						$this->assertEquals($byCharacter, $filter->getConvertByCharacter());

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
	 * Tests what happens when we disable transliteration.
	 */
	public function testDisabledTransliteration()
	{
		$encodings = [ 'UTF-8' ];

		$paths = [
			'',
			DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
			DIRECTORY_SEPARATOR . 'spacey namey' . DIRECTORY_SEPARATOR,
		];

		$tests = [
			[
				'input' => 'Sláinte',
				'output' => 'Sl_inte'
			],

			[
				'input' => 'Anführungszeichen',
				'output' => 'Anf_hrungszeichen'
			],

			[
				'input' => '“We’ve been here”',
				'output' => '_We_ve been here_'
			],

			[
				'input' => '14.95 €',
				'output' => '14.95 _'
			],

			[
				'input' => '†, ‡, ‰, •, 3–4, —, −5, +5, ™, …',
				'output' => '_, _, _, _, 3_4, _, _5, +5, _, _'
			],

			[
				'input' => 'Hello world, Καλημέρα κόσμε, コンニチハ',
				'output' => 'Hello world, ________ _____, _____',
			],

			[
				'input' => '⡌⠁⠧⠑ ⠼⠁⠒  ⡍⠜⠇⠑⠹⠰⠎ ⡣⠕⠌',
				'output' => '____ ___  _______ ___'
			],

			[
				'input' => 'ði ıntəˈnæʃənəl fəˈnɛtık əsoʊsiˈeıʃn',
				'output' => '_i _nt__n___n_l f__n_t_k _so_si_e__n'
			],

		];


		$filter = new Ascii();
		$filter->setTransliteration(false);
		$this->assertEquals(false, $filter->getTransliteration());

		foreach ($tests as $test) {
			foreach ($encodings as $encoding) {
				foreach ($paths as $path) {
					foreach ([true, false] as $byCharacter) {
						$filter->setConvertByCharacter($byCharacter);
						$this->assertEquals($byCharacter, $filter->getConvertByCharacter());

						$this->assertEquals(
							$path . $test['output'],
							$filter->filter($path . $test['input'], $encoding),
							'Disabling transliteration failed'
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

		$filter = new Ascii();

		foreach ($this->tests as $test) {
			if (isset($test['minimumIconvVersion']) && version_compare($test['minimumIconvVersion'], ICONV_VERSION, '>')) {
				$this->markTestIncomplete('Some strings require iconv > ' . $test['minimumIconvVersion'] . '; system version is: '. ICONV_VERSION);
				continue;
			}
			foreach ($encodings as $encoding) {
				foreach ($paths as $path) {
					foreach ([true, false] as $byCharacter) {
						$filter->setConvertByCharacter($byCharacter);

						$this->assertEquals($byCharacter, $filter->getConvertByCharacter());

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
