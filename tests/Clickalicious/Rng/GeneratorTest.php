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

namespace Clickalicious\Rng;

/**
 * Class GeneratorTest
 *
 * @package Rng
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
     * Test: Get instance with mode OpenSSL.
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
        new Generator(PHP_INT_MAX);
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
