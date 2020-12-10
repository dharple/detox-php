<?php
/**
 * Detox (https://github.com/dharple/detox/)
 *
 * @link      https://github.com/dharple/detox/
 * @copyright Copyright (c) 2017 Doug Harple
 * @license   https://github.com/dharple/detox/blob/master/LICENSE
 * @since     File available since Release 2.0.0
 */

namespace Outsanity\Detox\Tests\Filter;

use Outsanity\Detox\Filter\Wipeup;

/**
 * Tests the wipeup filter.
 *
 * @since      Class available since Release 2.0.0
 */
class WipeupTest 
	extends FilterTest
{

	/**
	 * Main set of tests
	 *
	 * @var array[]
	 */
	protected $tests = [

		[
			'input'        => 'this-----changes-----everything.jpg',
			'output.rt.rl' => 'this-changes-everything.jpg',
			'output.rt.xx' => 'this-changes-everything.jpg',
			'output.xx.rl' => 'this-changes-everything.jpg',
			'output.xx.xx' => 'this-changes-everything.jpg',
		],

		[
			'input'        => "why___don't___you___shine.png",
			'output.rt.rl' => "why_don't_you_shine.png",
			'output.rt.xx' => "why_don't_you_shine.png",
			'output.xx.rl' => "why_don't_you_shine.png",
			'output.xx.xx' => "why_don't_you_shine.png",
		],

		[
			'input'        => '_-_-_-_-a----s_____-p_-_-_---e---_----._n',
			'output.rt.rl' => 'a-s-p-e.n',
			'output.rt.xx' => '-a-s-p-e.n',
			'output.xx.rl' => 'a-s-p-e-._n',
			'output.xx.xx' => '-a-s-p-e-._n',
		],

		[
			'input'        => '#8 - Number 5.ogg',
			'output.rt.rl' => '8 - Number 5.ogg',
			'output.rt.xx' => '#8 - Number 5.ogg',
			'output.xx.rl' => '8 - Number 5.ogg',
			'output.xx.xx' => '#8 - Number 5.ogg',
		],

		[
			'input'        => '_simple_',
			'output.rt.rl' => 'simple',
			'output.rt.xx' => '_simple',
			'output.xx.rl' => 'simple_',
			'output.xx.xx' => '_simple_',
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

		$filter = new Wipeup();

		foreach ($this->tests as $test) {
			foreach ($encodings as $encoding) {
				foreach ($paths as $path) {
					foreach (array(true, false) as $removeLeading) {
						$filter->setRemoveLeading($removeLeading);

						foreach (array(true, false) as $removeTrailing) {
							$filter->setRemoveTrailing($removeTrailing);

							$this->assertEquals($removeLeading, $filter->getRemoveLeading());
							$this->assertEquals($removeTrailing, $filter->getRemoveTrailing());

							$expectedLabel = 'output.' . 
								($removeTrailing ? 'rt' : 'xx') . '.' .
								($removeLeading ? 'rl' : 'xx');

							$this->assertEquals(
								$path . $test[$expectedLabel],
								$filter->filter($path . $test['input'], $encoding),
								'Wipeup filter failed.  Encoding is set to: ' . $encoding .
								', remove trailing: ' . ($removeTrailing ? 'enabled' : 'disabled') .
								', remove leading: ' . ($removeLeading ? 'enabled' : 'disabled')
							);
						}
					}
				}
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

		$filter = new Wipeup();

		foreach ($this->tests as $test) {
			foreach ($encodings as $encoding) {
				foreach ($paths as $path) {
					foreach (array(true, false) as $removeLeading) {
						$filter->setRemoveLeading($removeLeading);

						foreach (array(true, false) as $removeTrailing) {
							$filter->setRemoveTrailing($removeTrailing);

							$this->assertEquals($removeLeading, $filter->getRemoveLeading());
							$this->assertEquals($removeTrailing, $filter->getRemoveTrailing());

							$expectedLabel = 'output.' . 
								($removeTrailing ? 'rt' : 'xx') . '.' .
								($removeLeading ? 'rl' : 'xx');

							$original = mb_convert_encoding($path . $test['input'], $encoding, 'UTF-8');
							$expected = mb_convert_encoding($path . $test[$expectedLabel], $encoding, 'UTF-8');

							$this->assertEquals(
								$expected,
								$filter->filter($original, $encoding),
								'Wipeup filter failed.  Encoding is set to: ' . $encoding . ', source is: ' . $test['input'] .
								', remove trailing: ' . ($removeTrailing ? 'enabled' : 'disabled') .
								', remove leading: ' . ($removeLeading ? 'enabled' : 'disabled')
							);

						}
					}
				}
			}
		}
	}

}
