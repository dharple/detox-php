<?php

/**
 * This file is part of the Detox package.
 *
 * (c) Doug Harple <detox.dharple@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Outsanity\Detox\Filter;

use Outsanity\Detox\Helper\Encoding;

/**
 * Replaces any % followed by two hex characters with the appropriate UTF-8
 * character.
 *
 * Replaces + with a space.
 */
class Uncgi implements FilterInterface
{
    use Encoding;

    /**
     * Filters a filename based on the rules of the filter.
     *
     * It's important to note that this only operates on the filename; any
     * additional path information will be returned as passed.
     *
     * @param string  $filename The filename to filter.
     * @param ?string $encoding The encoding that the filename is in.
     *
     * @return string The filtered filename.
     */
    public function filter(string $filename, ?string $encoding = null): string
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
