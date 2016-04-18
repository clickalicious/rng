<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Clickalicious;

/**
 * Rng.
 *
 * GeneratorTest.php - Unit tests for RNG functionality.
 *
 * PHP versions 5.4
 *
 * LICENSE:
 * Rng - Random number generator for PHP
 *
 * Copyright (c) 2015 - 2016, Benjamin Carl
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 *
 * - Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 *
 * - Neither the name of Rng nor the names of its
 * contributors may be used to endorse or promote products derived from
 * this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * Please feel free to contact us via e-mail: opensource@clickalicious.de
 *
 * @category  Clickalicious
 *
 * @author    Benjamin Carl <opensource@clickalicious.de>
 * @copyright 2015 - 2016 Benjamin Carl
 * @license   https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 *
 * @version   Git: $Id$
 *
 * @link      https://github.com/clickalicious/Rng
 */

use Clickalicious\Rng\Generator;

/**
 * Rng.
 *
 * Unit tests for client functionality.
 *
 * @category  Clickalicious
 *
 * @author    Benjamin Carl <opensource@clickalicious.de>
 * @copyright 2015 - 2016 Benjamin Carl
 * @license   https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 *
 * @version   Git: $Id$
 *
 * @link      https://github.com/clickalicious/Rng
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test: Get instance.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testInstance()
    {
        $this->assertInstanceOf(
            'Clickalicious\Rng\Generator',
            new Generator()
        );
    }

    /**
     * Test: Get instance with mode default.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testCreatingInstanceByModeDefault()
    {
        $generator = new Generator();

        $this->assertInstanceOf(
            'Clickalicious\Rng\Generator',
            $generator
        );
    }

    /**
     * Test: Get instance with mode php default.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testCreatingInstanceByModePhpDefault()
    {
        $generator = new Generator(
            Generator::MODE_PHP_DEFAULT
        );

        $this->assertInstanceOf(
            'Clickalicious\Rng\Generator',
            $generator
        );
    }

    /**
     * Test: Get instance with mode php mersenne twister default.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testCreatingInstanceByModePhpMersenneTwister()
    {
        $generator = new Generator(
            Generator::MODE_PHP_MERSENNE_TWISTER
        );

        $this->assertInstanceOf(
            'Clickalicious\Rng\Generator',
            $generator
        );
    }

    /**
     * Test: Get instance with mode php mcrypt extension.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testCreatingInstanceByModePhpMcrypt()
    {
        $generator = new Generator(
            Generator::MODE_MCRYPT
        );

        $this->assertInstanceOf(
            'Clickalicious\Rng\Generator',
            $generator
        );
    }

    /**
     * Test: Get instance with mode php mcrypt extension.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testCreatingInstanceByModeOpenSsl()
    {
        $generator = new Generator(
            Generator::MODE_OPEN_SSL
        );

        $this->assertInstanceOf(
            'Clickalicious\Rng\Generator',
            $generator
        );
    }

    /**
     * Test generating random number with default setting.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testGeneratingRandomIntegerByDefaults()
    {
        $generator = new Generator();
        $this->assertInternalType('int', $generator->generate());
    }

    /**
     * Test generating random number with php default implementation.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testGeneratingRandomIntegerByPhpDefault()
    {
        $generator = new Generator(Generator::MODE_PHP_DEFAULT);
        $this->assertInternalType('int', $generator->generate());
    }

    /**
     * Test generating random number with php new mersenne twister algorithm.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testGeneratingRandomIntegerByPhpMersenneTwister()
    {
        $generator = new Generator(Generator::MODE_PHP_MERSENNE_TWISTER);
        $this->assertInternalType('int', $generator->generate());
    }

    /**
     * Test generating random number with php mcrypt extension algorithm.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testGeneratingRandomIntegerByPhpMcrypt()
    {
        $generator = new Generator(Generator::MODE_MCRYPT);
        $this->assertInternalType('int', $generator->generate());
    }

    /**
     * Test generating random number with php openssl extension algorithm.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testGeneratingRandomIntegerByOpenSsl()
    {
        $generator = new Generator(Generator::MODE_OPEN_SSL);
        $this->assertInternalType('int', $generator->generate());
    }

    /**
     * Test: Test passing invalid/unknown mode.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @expectedException \Clickalicious\Rng\Exception
     */
    public function testCreatingInstanceByInvalidModeException()
    {
        $generator = new Generator(PHP_INT_MAX);
    }

    /**
     * Test: Test generating seeds.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testGeneratingIntegerSeed()
    {
        $generator = new Generator();
        $seed      = $generator->generateSeed();

        $this->assertInternalType('int', $seed);
    }

    /**
     * Test: Test generating instance with seed.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testCreatingInstanceByModeMcryptWithSeed()
    {
        $seed      = 123456;
        $generator = new Generator(Generator::MODE_MCRYPT, $seed);
        $this->assertSame($seed, $generator->getSeed());
    }

    /**
     * Test: Test generating instance with seed.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testCreatingInstanceByModeOpenSslWithSeed()
    {
        $seed      = 123456;
        $generator = new Generator(Generator::MODE_OPEN_SSL, $seed);
        $this->assertSame($seed, $generator->getSeed());
    }

    /**
     * Test: Test generating instance with seed.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testCreatingInstanceByModePhpDefaultWithSeed()
    {
        $seed      = 123456;
        $generator = new Generator(Generator::MODE_PHP_DEFAULT, $seed);
        $this->assertSame($seed, $generator->getSeed());
    }

    /**
     * Test: Test generating instance with seed.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testCreatingInstanceByModePhpMersenneTwisterWithSeed()
    {
        $seed      = 123456;
        $generator = new Generator(Generator::MODE_PHP_MERSENNE_TWISTER, $seed);
        $this->assertSame($seed, $generator->getSeed());
    }

    /**
     * Test: Test generating instance with seed.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @expectedException \Clickalicious\Rng\Exception
     */
    public function testTryToSetVariableWithInvalidTypeForSeed()
    {
        $seed      = 'Foo';
        $generator = new Generator();
        $generator
            ->setSeed($seed);
    }

    /**
     * Test: Test generating random bytes for public API.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testGenerateRandomBytesByModePhpInternalOnPublicApi()
    {
        $generator = new Generator();

        $randomBytes = $generator->getRandomBytes(4096);
        $this->assertEquals(4096, strlen($randomBytes), 'Validating result length in bytes ...');
    }

    /**
     * Test: Test generating random bytes for public API.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testGenerateRandomBytesByModePhpMcryptOnPublicApi()
    {
        $generator = new Generator();

        $randomBytes = $generator->getRandomBytes(4096, Generator::MODE_MCRYPT);
        $this->assertEquals(4096, strlen($randomBytes), 'Validating result length in bytes ...');
    }

    /**
     * Test: Test generating random bytes for public API.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testGenerateRandomBytesByModeOpenSslOnPublicApi()
    {
        $generator = new Generator();

        $randomBytes = $generator->getRandomBytes(4096, Generator::MODE_OPEN_SSL);
        $this->assertEquals(4096, strlen($randomBytes), 'Validating result length in bytes ...');
    }

    /**
     * Test: Test setting an invalid mode.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @expectedException \Clickalicious\Rng\Exception
     */
    public function testTryToSetInvalidEncryptionMode()
    {
        $generator = new Generator();
        $generator->setMode(4096);
    }

    /**
     * Test: Test setting a seed value on init.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testPassingSeedToConstructor()
    {
        $generator = new Generator(Generator::MODE_OPEN_SSL, time());
        $this->assertInstanceOf('Clickalicious\Rng\Generator', $generator);
    }
}
