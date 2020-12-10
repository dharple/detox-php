<?php

/**
 * This file is part of the Detox package.
 *
 * (c) Doug Harple <detox.dharple@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Outsanity\Detox\Helper;

/**
 * Provides core functionality for the filters.
 */
trait Encoding
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
     * @var ?string
     */
    protected $systemInternalEncoding = null;

    /**
     * Holds the system regex encoding.
     *
     * This is the regex encoding that the system has defaulted to.
     *
     * @var ?string
     */
    protected $systemRegexEncoding = null;

    /**
     * Converts the operational filename back into the source encoding.
     *
     * @param string $filename The filename to filter.
     * @param string $encoding The source encoding for the filename.
     *
     * @return string The filtered filename.
     */
    protected function convertFromOperationalEncoding(
        $filename,
        $encoding = null
    ) {
        $this->prepareEncodings();

        $encoding = $encoding ?: $this->systemInternalEncoding;

        if ($encoding != $this->operationalEncoding) {
            $filename = mb_convert_encoding(
                $filename,
                $encoding,
                $this->operationalEncoding
            );
        }

        return $filename;
    }

    /**
     * Converts the source filename into the operational encoding.
     *
     * @param string $filename The filename to filter.
     * @param string $encoding The source encoding for the filename.
     *
     * @return string The filtered filename.
     */
    protected function convertToOperationalEncoding(
        $filename,
        $encoding = null
    ) {
        $this->prepareEncodings();

        $encoding = $encoding ?: $this->systemInternalEncoding;

        if ($encoding != $this->operationalEncoding) {
            $filename = mb_convert_encoding(
                $filename,
                $this->operationalEncoding,
                $encoding
            );
        }

        return $filename;
    }

    /**
     * Prepares the filename
     *
     * @param string $filename The filename to filter.
     *
     * @return string The filtered filename.
     */
    protected function getBaseFilename($filename)
    {
        return basename($filename);
    }

    /**
     * Prepares the MB function encodings for operation.
     */
    protected function prepareEncodings()
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
     * Replaces the base filename given the original filename.
     *
     * @param string $filename        The original filenmae.
     * @param string $newBaseFilename The new base filename.
     *
     * @return string The new filename.
     */
    protected function replaceBaseFilename($filename, $newBaseFilename)
    {
        $dirname = dirname($filename);
        if ($dirname == '.' && mb_substr($filename, 0, 1) !== '.') {
            return $newBaseFilename;
        }

        return $dirname . DIRECTORY_SEPARATOR . $newBaseFilename;
    }

    /**
     * Restores the MB function encodings after operation.
     */
    protected function restoreEncodings()
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
