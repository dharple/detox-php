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

use Outsanity\Detox\Filter\Safe;

/**
 * Tests the safe filter.
 *
 * @since      Class available since Release 2.0.0
 */
class SafeTest extends FilterTest
{

    /**
     * Main set of tests
     *
     * @var array[]
     */
    protected $tests = [

        [
            'input' =>  'You Need!To"Do$Something*About:That:Line;.jpg',
            'output' => 'You_Need_To_Do_Something_About_That_Line_.jpg'
        ],

        [
            'input' =>  "there's<no>point?in@any`of|this.png",
            'output' => 'there_s_no_point_in_any_of_this.png',
        ],

        [
            'input' =>  'you(should)not[have]come{here}like=this.xz',
            'output' => 'you-should-not-have-come-here-like-this.xz',
        ],

        [
            'input' =>  'nothing#changes%here+and,I-do^not_care~',
            'output' => 'nothing#changes%here+and,I-do^not_care~',
        ],

        [
            'input' =>  'what!do(you)want&why#have@you%come?.blah',
            'output' => 'what_do-you-want_and_why#have_you%come_.blah',
        ],

        [
            'input' =>  "tabs\tbreak\tdetox.php",
            'output' => 'tabs_break_detox.php',
        ],

        [
            'input' =>  'slashes\\here',
            'output' => 'slashes_here',
        ],


    ];

    /**
     * Tests basic translation with a variety of encodings.
     */
    public function testBasicTranslation()
    {
        $encodings = [ 'UTF-8', 'ISO-8859-1', 'ISO-8859-15', 'Windows-1252' ];

        $paths = [
            '',
            DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR . 'spacey namey' . DIRECTORY_SEPARATOR,
        ];

        $filter = new Safe();

        foreach ($this->tests as $test) {
            foreach ($encodings as $encoding) {
                foreach ($paths as $path) {
                    $this->assertEquals(
                        $path . $test['output'],
                        $filter->filter($path . $test['input'], $encoding),
                        'Safe filter failed.  Encoding is set to: ' . $encoding
                    );
                }
            }
        }
    }

    /**
     * Tests basic translation with a variety of encodings, adjusting the
     * system encoding every time.
     */
    public function testSystemEncodings()
    {
        $encodings = [ 'UTF-8', 'ISO-8859-1', 'ISO-8859-15' ];

        $paths = [
            '',
            DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR . 'spacey namey' . DIRECTORY_SEPARATOR,
        ];

        $filter = new Safe();

        foreach ($encodings as $encoding) {
            mb_internal_encoding($encoding);
            mb_regex_encoding($encoding);

            foreach ($this->tests as $test) {
                foreach ($paths as $path) {
                    $this->assertEquals(
                        $path . $test['output'],
                        $filter->filter($path . $test['input']),
                        'Safe filter failed.  Encoding is set to: ' . $encoding
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


    /**
     * Tests wide character sets
     */
    public function testWideEncodings()
    {
        $content = [
            'UTF-8' => 'ℨℒℇℇ†•.c',
            'UCS-2' => "\x21\x28\x21\x12\x21\x07\x21\x07\x20\x20\x20\x22\x00\x2e\x00\x63"
        ];

        // sanity check our inputs

        $this->assertEquals(
            $content['UTF-8'],
            mb_convert_encoding($content['UCS-2'], 'UTF-8', 'UCS-2')
        );

        $this->assertEquals(
            $content['UCS-2'],
            mb_convert_encoding($content['UTF-8'], 'UCS-2', 'UTF-8')
        );

        $paths = [
            '',
            DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR . 'spacey namey' . DIRECTORY_SEPARATOR,
        ];

        $filter = new Safe();

        foreach ($paths as $path) {
            foreach ($content as $encoding => $filename) {
                // output should be the same

                $expected = mb_convert_encoding($path, $encoding, 'UTF-8') . $filename;

                $this->assertEquals(
                    $expected,
                    $filter->filter($expected, $encoding),
                    'Safe filter failed on wide encodings.  Encoding is set to: ' . $encoding . ', path is: ' . $path
                );
            }
        }
    }

    /**
     * Tests basic translation with a variety of encodings.
     */
    public function testWideTranslation()
    {
        $encodings = [ 'UCS-2', 'UCS-4' ];

        $paths = [
            '',
            DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR . 'spacey namey' . DIRECTORY_SEPARATOR,
        ];

        $filter = new Safe();

        foreach ($this->tests as $test) {
            foreach ($encodings as $encoding) {
                foreach ($paths as $path) {
                    $original = mb_convert_encoding($path . $test['input'], $encoding, 'UTF-8');
                    $expected = mb_convert_encoding($path . $test['output'], $encoding, 'UTF-8');

                    $this->assertEquals(
                        $expected,
                        $filter->filter($original, $encoding),
                        'Safe filter failed.  Encoding is set to: ' . $encoding . ', source is: ' . $test['input']
                    );
                }
            }
        }
    }
}
