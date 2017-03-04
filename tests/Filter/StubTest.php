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

use PHPUnit\Framework\TestCase;

/**
 * Full coverage; confirms that we don't double unset the filter.
 *
 * @since      Class available since Release 2.0.0
 */
class StubTest 
	extends TestCase
{

	/**
	 * Holds the operation encoding.
	 *
	 * This is the encoding that we do our work in.
	 *
	 * @var string
	 */
	protected $operationalEncoding = 'UTF-8';

	/**
	 * Holds the system internal encoding.
	 *
	 * This is the encoding that the system has defaulted to.
	 *
	 * @var string
	 */
	protected $systemInternalEncoding = null;

	/**
	 * Holds the system regex encoding.
	 *
	 * This is the regex encoding that the system has defaulted to.
	 *
	 * @var string
	 */
	protected $systemRegexEncoding = null;

	/**
	 * Prepares the MB function encodings for operation.
	 */
	protected function setUp()
	{
		if ($this->systemInternalEncoding !== null) {
			return;
		}

		$this->systemInternalEncoding = mb_internal_encoding();
		$this->systemRegexEncoding = mb_regex_encoding();

		if ($this->systemInternalEncoding != $this->operationalEncoding) {
			mb_internal_encoding($this->operationalEncoding);
		}

		if ($this->systemRegexEncoding != $this->operationalEncoding) {
			mb_regex_encoding($this->operationalEncoding);
		}

	}

	/**
	 * Restores the MB function encodings after operation.
	 */
	protected function tearDown()
	{
		if ($this->systemInternalEncoding === null) {
			return;
		}

		if ($this->systemInternalEncoding != $this->operationalEncoding) {
			mb_internal_encoding($this->systemInternalEncoding);
		}

		if ($this->systemRegexEncoding != $this->operationalEncoding) {
			mb_regex_encoding($this->systemRegexEncoding);
		}

		$this->systemInternalEncoding = null;
		$this->systemRegexEncoding = null;

	}

	/**
	 * Tests basic translation with a variety of encodings, adjusting the
	 * system encoding every time.
	 */
	public function testSystemEncodings()
	{
		$encodings = [ 'UTF-8', 'ISO-8859-1', 'ISO-8859-15' ];

		$paths = [
			'',
			DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
			DIRECTORY_SEPARATOR . 'spacey namey' . DIRECTORY_SEPARATOR,
		];

		$tests = [];

		$tests[] = [
			'input' =>  'You Need!To"Do$Something*About:That:Line;.jpg',
			'output' => 'You Need!To"Do$Something*About:That:Line;.jpg',
		];

		$tests[] = [
			'input' =>  "there's<no>point?in@any`of|this.png",
			'output' => "there's<no>point?in@any`of|this.png",
		];

		$tests[] = [
			'input' =>  'you(should)not[have]come{here}like=this.xz',
			'output' => 'you(should)not[have]come{here}like=this.xz',
		];

		$tests[] = [
			'input' =>  'nothing#changes%here+and,I-do^not_care~',
			'output' => 'nothing#changes%here+and,I-do^not_care~',
		];

		$tests[] = [
			'input' =>  'what!do(you)want&why#have@you%come?.blah',
			'output' => 'what!do(you)want&why#have@you%come?.blah',
		];

		$filter = new StubFilter();

		foreach ($encodings as $encoding) {
			mb_internal_encoding($encoding);
			mb_regex_encoding($encoding);

			foreach ($tests as $test) {
				foreach ($paths as $path) {
					$this->assertEquals(
						$path . $test['output'],
						$filter->filter($path . $test['input']),
						'Stub filter failed.  Encoding is set to: ' . $encoding
					);
				}
			}

			$this->assertEquals($encoding, mb_internal_encoding(),
				'Encoding does not match what we set it to.');
		}
	}

}
