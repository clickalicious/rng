<?php

/**
 * (The MIT license)
 * Copyright 2017 clickalicious, Benjamin Carl
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
 * BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
 * ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

require_once __DIR__ . '/vendor/autoload.php';

/*
 * Default random number generating easily.
 * Generate a random number with best rng available
 * between 1 and 10:
 */
$generator = new \Clickalicious\Rng\Generator();
$number    = $generator->generate(1, 10);
echo $number.PHP_EOL;


/*
 * Random number generating easily.
 * Generate a random number with PHP's default rng
 * between 1 and 10:
 */
$generator = new \Clickalicious\Rng\Generator(\Clickalicious\Rng\Generator::MODE_PHP_DEFAULT);
$number    = $generator->generate(1, 10);
echo $number.PHP_EOL;


/*
 * Random number generating easily.
 * Generate a random number with PHP's mersenne twister rng
 * between 1 and 10:
 */
$generator = new \Clickalicious\Rng\Generator(\Clickalicious\Rng\Generator::MODE_PHP_MERSENNE_TWISTER);
$number    = $generator->generate(1, 10);
echo $number.PHP_EOL;
