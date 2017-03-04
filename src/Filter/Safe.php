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

/**
 * Replaces particularly troublesome characters in a filename.
 *
 * Replaces the following characters with _
 *
 *   SPACE, !, #, ", $, ', *, /, :, ;, <, >, ?, @, \, `, |
 *
 * (Note that depending on your OS, / or \ will be used for directory
 * separation, and will (hopefully) not be available for filenames.)
 *
 * Replaces the following characters with -
 *
 *   (, ), [, ], {, }, =
 *
 * (Note that earlier versions of detox left = alone.)
 *
 * Replaces the following character with _and_
 * 
 *   &
 *
 * @since      Class available since Release 2.0.0
 */
class Safe
	extends AbstractFilter
	implements FilterInterface
{

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

		$baseFilename = mb_ereg_replace('[ !"$\'*\/:;<>?@\\`|]', '_', $baseFilename);

		$baseFilename = mb_ereg_replace('[()\[\]{}=]', '-', $baseFilename);

		$baseFilename = mb_ereg_replace('[&]', '_and_', $baseFilename);

		$filename = $this->replaceBaseFilename($filename, $baseFilename);

		$filename = $this->convertFromOperationalEncoding($filename, $encoding);

		$this->restoreEncodings();

		return $filename;
	}

}
