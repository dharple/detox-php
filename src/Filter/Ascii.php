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
 * Transliterates any characters into ASCII.
 *
 * Note that this does *not* change the underlying encoding.  If you pass in a
 * filename in UCS-2, we will return a filename in UCS-2, with any character
 * not in the ASCII charset transliterated into it.
 *
 * @since      Class available since Release 2.0.0
 */
class Ascii implements FilterInterface
{
    use Encoding;

    /**
     * Whether or not we should always operate character by character.
     *
     * @var boolean
     */
    protected $convertByCharacter = false;

    /**
     * Whether or not we should transliterate characters.
     *
     * Disabling this means that any character above 0x7F in US-ASCII will
     * become '_';
     *
     * @var boolean
     */
    protected $transliteration = true;

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
     * Returns the target encoding.
     *
     * @return string
     */
    protected function getTargetEncoding()
    {
        return $this->transliteration ? 'ASCII//TRANSLIT' : 'ASCII';
    }

    /**
     * Returns the current state of $transliteration.
     *
     * @return boolean
     */
    public function getTransliteration()
    {
        return $this->transliteration;
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
        $this->convertByCharacter = (bool) $convertByCharacter;

        return $this;
    }

    /**
     * Sets the current state of $transliteration.
     *
     * @param boolean $transliteration;
     *
     * @return $this Support method chaining.
     */
    public function setTransliteration($transliteration)
    {
        $this->transliteration = (bool) $transliteration;

        return $this;
    }

    /**
     * Quick and dirty: try iconv on the whole string.
     *
     * @param string $filename
     *
     * @return string
     */
    protected function convertString($filename)
    {
        $check = @iconv(
            $this->operationalEncoding,
            $this->getTargetEncoding(),
            $filename
        );

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
        $targetEncoding = $this->getTargetEncoding();

        $output = '';
        for ($i = 0; $i < mb_strlen($filename); $i++) {
            $check = @iconv(
                $this->operationalEncoding,
                $targetEncoding,
                mb_substr($filename, $i, 1)
            );

            if ($check !== false) {
                $output .= $check;
            } else {
                $output .= '_';
            }
        }

        return $output;
    }
}
