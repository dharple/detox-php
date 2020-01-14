<?php

/**
 * This file is part of the Detox package.
 *
 * (c) Doug Harple <detox.dharple@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Detox\Filter;

use Detox\Helper\Encoding;

/**
 * Replaces particularly troublesome characters in a filename.
 *
 * Replaces the following characters with _
 *
 *   SPACE, TAB, !, #, ", $, ', *, /, :, ;, <, >, ?, @, \, `, |
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
 */
class Safe implements FilterInterface
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

        $baseFilename = str_replace('\\', '_', $baseFilename);

        $baseFilename = mb_ereg_replace('[ 	!"$\'*/:;<>?@`|]', '_', $baseFilename);

        $baseFilename = mb_ereg_replace('[()\[\]{}=]', '-', $baseFilename);

        $baseFilename = mb_ereg_replace('[&]', '_and_', $baseFilename);

        $filename = $this->replaceBaseFilename($filename, $baseFilename);

        $filename = $this->convertFromOperationalEncoding($filename, $encoding);

        $this->restoreEncodings();

        return $filename;
    }
}
