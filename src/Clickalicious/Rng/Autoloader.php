<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Clickalicious\Rng;

/**
 * Rng.
 *
 * Autoloader.php - Autoloader of Rng.
 *
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

/**
 * Rng.
 *
 * Autoloader.php - Autoloader of Rng.
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
class Autoloader
{
    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = [];

    /**
     * Register loader with SPL autoloader stack.
     */
    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Adds a base directory for a namespace prefix.
     *
     * @param string $prefix  The namespace prefix.
     * @param string $baseDir A base directory for class files in the
     *                        namespace.
     * @param bool   $prepend If true, prepend the base directory to the stack
     *                        instead of appending it; this causes it to be searched first rather
     *                        than last.
     */
    public function addNamespace($prefix, $baseDir, $prepend = false)
    {
        // normalize namespace prefix
        $prefix = trim($prefix, '\\').'\\';

        // normalize the base directory with a trailing separator
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

        // initialize the namespace prefix array
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = [];
        }

        // retain the base directory for the namespace prefix
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $baseDir);
        } else {
            array_push($this->prefixes[$prefix], $baseDir);
        }
    }

    /**
     * Loads the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     *
     * @return string|false The mapped file name on success, or boolean false on
     *                      failure.
     */
    public function loadClass($class)
    {
        // The current namespace prefix
        $prefix = $class;

        // Work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {

            // Retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);

            // The rest is the relative class name
            $relativeClass = substr($class, $pos + 1);

            // Try to load a mapped file for the prefix and relative class
            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);

            if (false !== $mappedFile) {
                return $mappedFile;
            }

            // Remove the trailing namespace separator for the next iteration of strrpos()
            $prefix = rtrim($prefix, '\\');
        }

        // Never found a mapped file
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix        The namespace prefix.
     * @param string $relativeClass The relative class name.
     *
     * @return false|string Boolean false if no mapped file can be loaded, or the
     *                      name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $relativeClass)
    {
        // Are there any base directories for this namespace prefix?
        if (false === isset($this->prefixes[$prefix])) {
            return false;
        }

        // Look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $baseDir) {
            /*
             replace the namespace prefix with the base directory,
             replace namespace separators with directory separators
             in the relative class name, append with .php
            */
            $file = $baseDir.str_replace('\\', '/', $relativeClass).'.php';

            // If the mapped file exists, require it
            if ($this->requireFile($file)) {
                // yes, we're done
                return $file;
            }
        }

        // Never found it
        return false;
    }

    /**
     * If a file exists, require it from the file system.
     *
     * @param string $file The file to require.
     *
     * @return bool True if the file exists, false if not.
     */
    protected function requireFile($file)
    {
        if (true === $status = file_exists($file)) {
            include_once $file;
        }

        return $status;
    }
}
