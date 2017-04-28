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
 * Class Generator
 *
 * @package Rng
 */
class Generator
{
    /**
     * The seed for the RNG.
     * Static to prevent double seeding.
     *
     * @var null
     */
    protected $seed;

    /**
     * The cryptographic quality switch.
     * Used in OpenSSL random byte generator for example.
     *
     * @var bool
     */
    protected $cryptographicStrong;

    /**
     * The active mode. Default set by constructor.
     *
     * @var int
     */
    protected $mode;

    /**
     * The valid modes for validation.
     *
     * @var array
     * @static
     */
    protected static $validModes = [
        self::MODE_PHP_DEFAULT,
        self::MODE_PHP_MERSENNE_TWISTER,
        self::MODE_OPEN_SSL,
    ];

    /**
     * PHP's default RNG
     * (e.g. srand() + rand()).
     *
     * @var int
     *
     * @see http://php.net/manual/de/function.srand.php
     *      http://php.net/manual/de/function.rand.php
     */
    const MODE_PHP_DEFAULT = 1;

    /**
     * Mersenne Twister Mode
     * (e.g. mt_srand() + mt_rand()).
     *
     * @var int
     *
     * @see http://de.wikipedia.org/wiki/Mersenne-Twister
     *      http://php.net/manual/de/function.mt-srand.php
     *      http://php.net/manual/de/function.mt-rand.php
     */
    const MODE_PHP_MERSENNE_TWISTER = 2;

    /**
     * MCRYPT based PHP /dev/urandom based PRNG implementation.
     *
     * @var int
     *
     * @see http://php.net/manual/de/intro.mcrypt.php
     *      http://mcrypt.sourceforge.net/
     * @deprecated
     */
    const MODE_MCRYPT = 4;

    /**
     * OpenSSL based PHP PRNG implementation.
     *
     * @var int
     *
     * @see http://php.net/manual/de/function.openssl-random-pseudo-bytes.php
     */
    const MODE_OPEN_SSL = 8;

    /**
     * Source for Open SSL random bytes.
     *
     * @var int
     */
    const SOURCE_OPEN_SSL = self::MODE_OPEN_SSL;

    /*------------------------------------------------------------------------------------------------------------------
    | INIT
    +-----------------------------------------------------------------------------------------------------------------*/

    /**
     * Constructor.
     *
     * @param int      $mode Mode used for generating random numbers.
     *                       Default is OPEN_SSL as the currently best practice for generating random numbers
     * @param int|null $seed Optional seed used for randomizer init
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function __construct(
        $mode = self::MODE_OPEN_SSL,
        $seed = null
    )
    {
        $this
            ->mode($mode);

        // Only seed if seed passed -> no longer required (since PHP 4.2.0)
        if ($seed !== null) {
            $this->seed($seed);
        }
    }

    /*------------------------------------------------------------------------------------------------------------------
    | PUBLIC API
    +-----------------------------------------------------------------------------------------------------------------*/

    /**
     * Generates and returns a (pseudo) random number.
     *
     * @param int $rangeMinimum The minimum value of range
     * @param int $rangeMaximum The maximum value of range
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return int The generated (pseudo) random number
     *
     * @throws \Clickalicious\Rng\Exception
     * @throws \Clickalicious\Rng\CryptographicWeaknessException
     */
    public function generate($rangeMinimum = 0, $rangeMaximum = PHP_INT_MAX)
    {
        switch ($this->getMode()) {

            case self::MODE_OPEN_SSL:
                $randomValue = $this->genericRand($rangeMinimum, $rangeMaximum, self::MODE_OPEN_SSL);
                break;

            case self::MODE_PHP_MERSENNE_TWISTER:
                $randomValue = $this->mtRand($rangeMinimum, $rangeMaximum);
                break;

            case self::MODE_PHP_DEFAULT:
            default:
                $randomValue = $this->rand($rangeMinimum, $rangeMaximum);
                break;
        }

        return $randomValue;
    }

    /**
     * Generate the seed from microtime.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return int The seed value
     */
    public function generateSeed()
    {
        list($usec, $sec) = explode(' ', microtime());

        return (int)($sec + strrev($usec * 1000000)) + 13;
    }

    /**
     * Returns random bytes secure for cryptographic context.
     *
     * @param int      $numberOfBytes Number of bytes to read and return.
     * @param int|null $source        Source of random bytes.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return string Random bytes
     *
     * @throws \Clickalicious\Rng\Exception
     * @throws \Clickalicious\Rng\CryptographicWeaknessException
     */
    public function getRandomBytes(
        $numberOfBytes = PHP_INT_MAX,
        $source = null
    )
    {
        switch ($source) {

            case self::MODE_OPEN_SSL:
                $randomBytes = $this->getRandomBytesFromOpenSSL($numberOfBytes);
                break;

            default:
                // http://php.net/manual/de/function.random-bytes.php - POLYFILL used for PHP < 7
                $randomBytes = random_bytes($numberOfBytes);
                break;
        }

        return $randomBytes;
    }

    /*------------------------------------------------------------------------------------------------------------------
    | INTERNAL API
    +-----------------------------------------------------------------------------------------------------------------*/

    /**
     * "rand" based randomize.
     *
     * @param int $rangeMinimum The minimum range border for randomizer
     * @param int $rangeMaximum The maximum range border for randomizer
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return int From *closed* interval [$min, $max]
     */
    protected function rand($rangeMinimum, $rangeMaximum)
    {
        return rand($rangeMinimum, $rangeMaximum);
    }

    /**
     * "mt_rand" based randomize.
     *
     * @param int $rangeMinimum The minimum range border for randomizer
     * @param int $rangeMaximum The maximum range border for randomizer
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return int From *closed* interval [$min, $max]
     */
    protected function mtRand($rangeMinimum, $rangeMaximum)
    {
        return mt_rand($rangeMinimum, $rangeMaximum);
    }

    /**
     * "OpenSSL" based equivalent to rand & mt_rand but better randomness.
     *
     * @param int $rangeMinimum The minimum range border for randomizer
     * @param int $rangeMaximum The maximum range border for randomizer
     * @param int $source       The source of the random bytes (OpenSSL, ...)
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return int From *closed* interval [$min, $max]
     *
     * @throws \Clickalicious\Rng\Exception
     * @throws \Clickalicious\Rng\CryptographicWeaknessException
     */
    protected function genericRand(
        $rangeMinimum,
        $rangeMaximum,
        $source = self::MODE_OPEN_SSL
    )
    {
        $diff = $rangeMaximum - ($rangeMinimum + 1);

        if ($diff > PHP_INT_MAX) {
            throw new Exception('Bad range');
        }

        // The largest *multiple* of diff less than our sample
        $ceiling = floor(PHP_INT_MAX / $diff) * $diff;

        do {
            switch ($source) {
                case self::MODE_OPEN_SSL:
                default:
                    $bytes = $this->getRandomBytesFromOpenSSL(PHP_INT_SIZE);
                    break;
            }

            /* @codeCoverageIgnoreStart */
            // Check for error
            if (false === $bytes || PHP_INT_SIZE !== strlen($bytes)) {
                throw new Exception(
                    sprintf(
                        'Failed to read %s bytes from %s.',
                        PHP_INT_SIZE,
                        'OpenSSL'
                    )
                );
            }
            /* @codeCoverageIgnoreEnd */

            if (PHP_INT_SIZE === 8) {
                // 64-bit versions
                list($higher, $lower) = array_values(unpack('N2', $bytes));
                $val = $higher << 32 | $lower;
            } else {
                // 32-bit versions
                $val = unpack('Nint', $bytes);
            }

            $val = $val['int'] & PHP_INT_MAX;
        } while ($val > $ceiling);

        // In the unlikely case our sample is bigger than largest multiple, just do over until it’s not any more.
        // Perfectly even sampling in our 0<output<diff domain is mathematically impossible unless the total number of
        // *valid* inputs is an exact multiple of diff.
        return $val % $diff + $rangeMinimum;
    }

    /**
     * Returns random bytes from OpenSSL.
     *
     * @param int $numberOfBytes Number of bytes to read and return
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return string The random bytes
     *
     * @throws \Clickalicious\Rng\CryptographicWeaknessException
     */
    protected function getRandomBytesFromOpenSSL($numberOfBytes)
    {
        $randomBytes = openssl_random_pseudo_bytes($numberOfBytes, $cryptographicStrong);

        $this->setCryptographicStrong($cryptographicStrong);

        if (false === $randomBytes || '' === $randomBytes || false === $cryptographicStrong) {
            throw new CryptographicWeaknessException(
                'Error fetching random bytes from OpenSSL.'
            );
        }

        return $randomBytes;
    }

    /**
     * Checks if requirements for mode are fulfilled.
     *
     * @param int $mode The mode to check requirements for
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return bool TRUE on success, otherwise FALSE
     *
     * @throws \Clickalicious\Rng\Exception
     */
    protected function checkRequirements($mode)
    {
        if (true !== in_array($mode, self::$validModes, true)) {
            throw new Exception(
                sprintf('Mode "%s" not supported. Supported: "%s"', $mode, var_export(self::$validModes, true))
            );
        }

        switch ($mode) {
            case self::MODE_OPEN_SSL:
            case self::MODE_PHP_DEFAULT:
            case self::MODE_PHP_MERSENNE_TWISTER:
            default:
                // Intentionally omitted cause not required - listed here for code quality and readability
                break;
        }

        return true;
    }

    /**
     * Setter for mode.
     *
     * @param int $mode The mode to set
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @throws \Clickalicious\Rng\Exception
     */
    public function setMode($mode)
    {
        // Check for requirements depending on mode
        if (true === $this->checkRequirements($mode)) {
            $this->mode = $mode;
        }
    }

    /**
     * Fluent setter for mode.
     *
     * @param int $mode The mode to set
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return $this Instance for chaining
     */
    public function mode($mode)
    {
        $this->setMode($mode);

        return $this;
    }

    /**
     * Getter for mode.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return int The active mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Setter for seed.
     *
     * @param int $seed The seed value
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @throws \Clickalicious\Rng\Exception
     */
    public function setSeed($seed)
    {
        if (is_int($seed) !== true) {
            throw new Exception(
                sprintf('The type of the seed value "%s" need to be int. You passed a(n) "%s".', $seed, gettype($seed))
            );
        }

        // We need to call different methods depending on chosen source
        switch ($this->getMode()) {

            case self::MODE_PHP_MERSENNE_TWISTER:
                mt_srand($seed);
                break;

            case self::MODE_PHP_DEFAULT:
                srand($seed);
                break;

            case self::MODE_OPEN_SSL:
            default:
                // Intentionally left blank
                break;
        }

        $this->seed = $seed;
    }

    /**
     * Setter for seed.
     *
     * @param int $seed The seed value
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return $this Instance for chaining
     */
    public function seed($seed)
    {
        $this->setSeed($seed);

        return $this;
    }

    /**
     * Getter for seed.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return int|null The seed value if set, otherwise FALSE
     */
    public function getSeed()
    {
        return $this->seed;
    }

    /**
     * Setter for cryptographicStrong.
     *
     * @param bool $cryptographicStrong TRUE to mark random bytes as cryptographic strong, FALSE to mark as weak.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    protected function setCryptographicStrong($cryptographicStrong)
    {
        $this->cryptographicStrong = $cryptographicStrong;
    }

    /**
     * Setter for cryptographicStrong.
     *
     * @param bool $cryptographicStrong TRUE to mark random bytes as cryptographic strong, FALSE to mark as weak.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return $this Instance for chaining
     */
    protected function cryptographicStrong($cryptographicStrong)
    {
        $this->setCryptographicStrong($cryptographicStrong);

        return $this;
    }

    /**
     * Getter for cryptographicStrong.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return bool TRUE if last generated bytes are created using a cryptographic strong algorithm, FALSE when using a
     *              weak algorithm, otherwise NULL if no bytes created at all.
     */
    public function getCryptographicStrong()
    {
        return $this->cryptographicStrong;
    }
}
