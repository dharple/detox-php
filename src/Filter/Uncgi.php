<?php
/**
 * Detox (https://github.com/dharple/detox/)
 *
 * @link      https://github.com/dharple/detox/
 * @copyright Copyright (c) 2017 Doug Harple
 * @license   https://github.com/dharple/detox/blob/master/LICENSE
 * @since     File available since Release 2.0.0
 */

namespace Detox\Filter;

use Detox\Helper\Encoding;

/**
 * Replaces any % followed by two hex characters with the appropriate UTF-8
 * character.
 *
 * Replaces + with a space.
 *
 * @since      Class available since Release 2.0.0
 */
class Uncgi
	implements FilterInterface
{
	use Encoding;

	/**
	 * Filters a filename based on the rules of the filter.
	 *
	 * It's important to note that this only operates on the filename; any
	 * additional path information will be returned as passed.
	 *
	 * @param string $filename The filename to filter.
	 * @param string $encoding The encoding that the filename is in.
	 *
	 * @return string The filtered filename.
	 */
	public function filter($filename, $encoding = null)
	{
		$this->prepareEncodings();

		$filename = $this->convertToOperationalEncoding($filename, $encoding);

		$baseFilename = $this->getBaseFilename($filename);

		if (mb_strstr($baseFilename, '%') !== false || mb_strstr($baseFilename, '+') !== false) {
			$baseFilename = mb_eregi_replace('%(2F|00)', '_', $baseFilename);
			$baseFilename = urldecode($baseFilename);
		}

		$filename = $this->replaceBaseFilename($filename, $baseFilename);

		$filename = $this->convertFromOperationalEncoding($filename, $encoding);

		$this->restoreEncodings();

		return $filename;
	}

}
