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

/**
 * Full coverage; confirms that we don't double unset the filter.
 *
 * @since      Class available since Release 2.0.0
 */
class StubTest extends FilterTest
{

    /**
     * Main set of tests
     *
     * @var array[]
     */
    protected $tests = [
        [
            'input' =>  'You Need!To"Do$Something*About:That:Line,.jpg',
            'output' => 'You Need!To"Do$Something*About:That:Line,.jpg',
        ],

        [
            'input' =>  "there's<no>point?in@any`of|this.png",
            'output' => "there's<no>point?in@any`of|this.png",
        ],

        [
            'input' =>  'you(should)not[have]come{here}like=this.xz',
            'output' => 'you(should)not[have]come{here}like=this.xz',
        ],

        [
            'input' =>  'nothing#changes%here+and,I-do^not_care~',
            'output' => 'nothing#changes%here+and,I-do^not_care~',
        ],

        [
            'input' =>  'what!do(you)want&why#have@you%come?.blah',
            'output' => 'what!do(you)want&why#have@you%come?.blah',
        ],
    ];

    /**
     * The stub filter doesn't actually do anything, aside from call a method
     * it isn't supposed to.
     */
    public function testDoubleResetEncoding()
    {
        $encodings = [ 'UTF-8', 'ISO-8859-1', 'ISO-8859-15' ];

        $paths = [
            '',
            DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR . 'spacey namey' . DIRECTORY_SEPARATOR,
        ];

        $filter = new StubFilter();

        foreach ($encodings as $encoding) {
            mb_internal_encoding($encoding);
            mb_regex_encoding($encoding);

            foreach ($this->tests as $test) {
                foreach ($paths as $path) {
                    $this->assertEquals(
                        $path . $test['output'],
                        $filter->filter($path . $test['input']),
                        'Stub filter failed.  Encoding is set to: ' . $encoding
                    );
                }
            }

            $this->assertEquals(
                $encoding,
                mb_internal_encoding(),
                'Encoding does not match what we set it to.'
            );
        }
    }
}
