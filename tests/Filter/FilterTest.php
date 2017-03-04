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
 * Base methods for filter tests.
 *
 * @since      Class available since Release 2.0.0
 */
abstract class FilterTest
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

}
