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
 * Replaces redundant characters inside of a filename.
 *
 * Reduces any series of "_" and "-" to a single character.  If "-" is present,
 * it takes precedence.
 *
 * If "removeTrailing" is set to true, then "." is added to the comparison, and
 * takes precedence.  This has the effect of reducing "-." or "._", etc, to
 * ".".
 *
 * Strips any "-", "_" or "#" from the beginning of a string.
 *
 * @since      Class available since Release 2.0.0
 */
class Wipeup
	extends AbstractFilter
	implements FilterInterface
{

	/**
	 * Whether or not to remove any "-", "_", or "#" that are sitting at the
	 * front of the filename.
	 *
	 * @var boolean
	 */
	protected $removeLeading = true;

	/**
	 * Whether or not to remove any "-" or "_" that are sitting next to a
	 * period.
	 *
	 * @var boolean
	 */
	protected $removeTrailing = true;

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

		$baseFilename = mb_ereg_replace('_+', '_', $baseFilename);

		$baseFilename = mb_ereg_replace('-+', '-', $baseFilename);

		$baseFilename = mb_ereg_replace('(_-|-_)[_-]*', '-', $baseFilename);

		if ($this->removeTrailing) {
			$baseFilename = mb_ereg_replace('[_-]?\.[_-]?', '.', $baseFilename);
		}

		if ($this->removeLeading) {
			$baseFilename = mb_ereg_replace('^[-_#]+', '', $baseFilename);
		}

		$filename = $this->replaceBaseFilename($filename, $baseFilename);

		$filename = $this->convertFromOperationalEncoding($filename, $encoding);

		$this->restoreEncodings();

		return $filename;
	}

	/**
	 * Returns the current state of $removeLeading.
	 *
	 * @return boolean
	 */
	public function getRemoveLeading()
	{
		return $this->removeLeading;
	}

	/**
	 * Sets the current state of $removeLeading.
	 *
	 * @param boolean $removeLeading;
	 *
	 * @return $this Support method chaining.
	 */
	public function setRemoveLeading($removeLeading)
	{
		$this->removeLeading = (boolean)$removeLeading;

		return $this;
	}

	/**
	 * Returns the current state of $removeTrailing.
	 *
	 * @return boolean
	 */
	public function getRemoveTrailing()
	{
		return $this->removeTrailing;
	}

	/**
	 * Sets the current state of $removeTrailing.
	 *
	 * @param boolean $removeTrailing;
	 *
	 * @return $this Support method chaining.
	 */
	public function setRemoveTrailing($removeTrailing)
	{
		$this->removeTrailing = (boolean)$removeTrailing;

		return $this;
	}

}
