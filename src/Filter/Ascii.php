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
 * Transliterates any characters into ASCII.
 *
 * Note that this does *not* change the underlying encoding.  If you pass in a
 * filename in UCS-2, we will return a filename in UCS-2, with any character
 * not in the ASCII charset transliterated into it.
 *
 * @since      Class available since Release 2.0.0
 */
class Ascii
	extends AbstractFilter
	implements FilterInterface
{

	/**
	 * Whether or not we should always operate character by character.
	 *
	 * @var boolean
	 */
	protected $convertByCharacter = false;

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

		// try translit first:

		$done = false;
		if (!$this->convertByCharacter) {
			try {
				$baseFilename = $this->convertString($baseFilename);
				$done = true;
			} catch (Exception $e) {
			}
		}

		if (!$done) {
			$baseFilename = $this->convertStringByCharacter($baseFilename);
		}

		$filename = $this->replaceBaseFilename($filename, $baseFilename);

		$filename = $this->convertFromOperationalEncoding($filename, $encoding);

		$this->restoreEncodings();

		return $filename;
	}

	/**
	 * Returns the current state of $convertByCharacter.
	 *
	 * @return boolean
	 */
	public function getConvertByCharacter()
	{
		return $this->convertByCharacter;
	}

	/**
	 * Sets the current state of $convertByCharacter.
	 *
	 * @param boolean $convertByCharacter;
	 *
	 * @return $this Support method chaining.
	 */
	public function setConvertByCharacter($convertByCharacter)
	{
		$this->convertByCharacter = (boolean)$convertByCharacter;

		return $this;
	}

	/**
	 * Quick and dirty: try iconv with //TRANSLIT.
	 *
	 * @param string $filename 
	 *
	 * @return string
	 */
	protected function convertString($filename)
	{
		$check = @iconv('UTF-8', 'ASCII//TRANSLIT', $filename);
		if ($check === false) {
			$error = error_get_last();
			throw new Exception($error['message']);
		}
		return $check;
	}

	/**
	 * Slow and painful: convert each character.
	 *
	 * @param string $filename 
	 *
	 * @return string
	 */
	protected function convertStringByCharacter($filename)
	{
		$output = '';
		for ($i = 0; $i < mb_strlen($filename); $i++) {
			$check = @iconv('UTF-8', 'ASCII//TRANSLIT',
				mb_substr($filename, $i, 1));

			if ($check !== false) {
				$output .= $check;
			} else {
				$output .= '_';
			}
		}

		return $output;
	}

}
