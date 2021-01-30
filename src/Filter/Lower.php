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
 * Forces all characters to lower case.
 */
class Lower implements FilterInterface
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

        $baseFilename = mb_strtolower($baseFilename);

        $filename = $this->replaceBaseFilename($filename, $baseFilename);

        $filename = $this->convertFromOperationalEncoding($filename, $encoding);

        $this->restoreEncodings();

        return $filename;
    }
}
