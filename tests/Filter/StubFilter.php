<?php

/**
 * Detox (https://github.com/dharple/detox/)
 *
 * @link      https://github.com/dharple/detox/
 * @copyright Copyright (c) Doug Harple
 * @license   https://github.com/dharple/detox/blob/master/LICENSE
 * @since     File available since Release 2.0.0
 */

namespace Outsanity\Detox\Tests\Filter;

use Outsanity\Detox\Helper\Encoding;
use Outsanity\Detox\Filter\FilterInterface;

/**
 * Stub for full coverage.
 *
 * @since      Class available since Release 2.0.0
 */
class StubFilter implements FilterInterface
{
    use Encoding;

    /**
     * Filters a filename based on the rules of the filter.
     *
     * It's important to note that this only operates on the filename; any
     * additional path information will be returned as passed.
     *
     * @param string $filename The filename to filter.
     * @param ?string $encoding The encoding that the filename is in.
     *
     * @return string The filtered filename.
     */
    public function filter(string $filename, ?string $encoding = null): string
    {
        $this->restoreEncodings();
        return $filename;
    }
}
