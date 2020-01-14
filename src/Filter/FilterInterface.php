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
 * Defines the interface for all detox filters.
 *
 * @since      Class available since Release 2.0.0
 */
interface FilterInterface
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
    public function filter($filename, $encoding = null);
}
