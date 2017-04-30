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

// Clean config
define('RNG_VISUAL_ITERATIONS', 100000);


// We generate a visual representation/demo of randomness for this implementations (PNG)
header('Content-type: image/png');

// Dimension of one tile
$width  = 800;
$height = 800;

// Our generators
$generators = [
    new \Clickalicious\Rng\Generator(\Clickalicious\Rng\Generator::MODE_PHP_DEFAULT),
    new \Clickalicious\Rng\Generator(\Clickalicious\Rng\Generator::MODE_PHP_MERSENNE_TWISTER),
    new \Clickalicious\Rng\Generator(\Clickalicious\Rng\Generator::MODE_OPEN_SSL),
];

$countGenerators = count($generators);

// Complete width for all generators
$totalWidth = $countGenerators * $width;
$img        = imagecreatetruecolor(count($generators) * $width, $height);

imagefilledrectangle($img, 0, 0, $totalWidth, $height, imagecolorallocate($img, 255, 255, 255));

// Iterate and draw
for ($i = 0; $i < $countGenerators; ++$i) {
    $color = imagecolorallocate($img, 0, 0, 0);
    $p     = 0;

    for ($j = 0; $j < RNG_VISUAL_ITERATIONS; ++$j) {
        $np = $generators[$i]->generate(0, $width);
        imagesetpixel($img, $p + ($width * $i), $np, $color);
        $p = $np;
    }
}

imagepng($img);
imagedestroy($img);
