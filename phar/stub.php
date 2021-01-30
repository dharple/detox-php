#!/usr/bin/env php
<?php

/**
 * This file is part of the detox-php package.
 *
 * (c) Doug Harple <dharple@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Phar::mapPhar('detox.phar');
include 'phar://detox.phar/bin/detox';
__HALT_COMPILER();

