<?php
/**
 * Detox (https://github.com/dharple/detox/)
 *
 * @link      https://github.com/dharple/detox/
 * @copyright Copyright (c) 2017 Doug Harple
 * @license   https://github.com/dharple/detox/blob/master/LICENSE
 * @since     File available since Release 2.0.0
 */

namespace DetoxTest;

use Detox\Filter\Ascii;
use Detox\Filter\Lower;
use Detox\Filter\Safe;
use Detox\Filter\Uncgi;
use Detox\Filter\Wipeup;
use Detox\Sequence;

/**
 * Tests the sequence handler.
 *
 * @since      Class available since Release 2.0.0
 */
class SequenceTest
{

	/**
	 * Main set of tests
	 *
	 * @var array[]
	 */
	protected $uncgiSafeWipeupTests = [
		[
			'input' => 'There%27s+nothing+to+see%2C+here%2C+so+move+on%21',
			'output' => "There_s_nothing_to_see_here_so_move_on",
		],

		[
			'input' => '%3Chtml%3E%3Chead%3E%3Ctitle%3Esomething%2Chere%3C%2Ftitle%3E%3C%2Fhead%3E%3Cbody%3E%3Cp%3Efilename.txt.%3C%2Fp%3E%3C%2Fbody%3E%3C%2Fhtml%3E',
			'output' => 'html_head_title_something_here_title_head_body_p_filename.txt.p_body_html',
		],

		[
			'input' => 'nothing',
			'output' => 'nothing',
		],

		[
			'input' => 'really%2Fbroken%00now',
			'output' => 'really_broken_now',
		],
	];

	/**
	 * Tests basic translation with a variety of encodings.
	 */
	public function testUncgiSafeWipeup()
	{
		$paths = [
			'',
			DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
			DIRECTORY_SEPARATOR . 'spacey namey' . DIRECTORY_SEPARATOR,
		];

		$sequence = new Sequence();
		$sequence
			->addFilter(new Uncgi())
			->addFilter(new Safe())
			->addFilter(new Wipeup());

		foreach ($this->uncgiSafeWipeupTests as $test) {
			foreach ($paths as $path) {
				$this->assertEquals(
					$test['output'],
					$sequence->filter($test['input']),
					'Sequence of Uncgi, Safe, Wipeup failed.'
				);
			}
		}
	}

}
