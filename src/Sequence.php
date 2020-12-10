<?php

/**
 * This file is part of the Detox package.
 *
 * (c) Doug Harple <detox.dharple@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Outsanity\Detox;

use Outsanity\Detox\Filter\FilterInterface;
use Outsanity\Detox\Helper\Encoding;

/**
 * Holds a sequence of filters to apply to filenames.
 */
class Sequence
{
    use Encoding;

    /**
     * A sequence of filters to apply.
     *
     * @var FilterInterface[]
     */
    protected $filters = [];

    /**
     * The input encoding to use.
     *
     * @var string
     */
    protected $inputEncoding = 'UTF-8';

    /**
     * The output encoding to use.
     *
     * @var string
     */
    protected $outputEncoding = 'UTF-8';

    /**
     * Adds a filter.  Filters will be applied to files in the order presented.
     *
     * @param FilterInterface $filter
     *
     * @return Sequence support method chaining
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Runs a sequence of filters against a filename.
     *
     * @param string $filename The filename to filter.
     *
     * @return string The filtered filename.
     */
    public function filter(string $filename)
    {
        $this->prepareEncodings();

        $filename = $this->convertToOperationalEncoding($filename, $this->inputEncoding);

        $baseFilename = $this->getBaseFilename($filename);

        foreach ($this->filters as $filter) {
            $baseFilename = $filter->filter($baseFilename, $this->operationalEncoding);
        }

        $filename = $this->replaceBaseFilename($filename, $baseFilename);

        $filename = $this->convertFromOperationalEncoding($filename, $this->outputEncoding);

        $this->restoreEncodings();

        return $filename;
    }
}
