<img src="https://avatars0.githubusercontent.com/u/26927954?v=3&s=80" align="right" />
---

![Logo of rng](docs/logo-large.png)

The secure **Pseudo Random Number Generator** `PRNG` for PHP.

| [![Build Status](https://travis-ci.org/clickalicious/rng.svg?branch=master)](https://travis-ci.org/clickalicious/rng) 	| [![Codacy branch grade](https://img.shields.io/codacy/grade/f53e4682e6524d44aedb454adce68a18/master.svg)](https://www.codacy.com/app/clickalicious/rng?utm_source=github.com&utm_medium=referral&utm_content=clickalicious/rng&utm_campaign=Badge_Grade)	| [![Codacy coverage](https://img.shields.io/codacy/coverage/f53e4682e6524d44aedb454adce68a18.svg)](https://www.codacy.com/app/clickalicious/rng?utm_source=github.com&utm_medium=referral&utm_content=clickalicious/rng&utm_campaign=Badge_Grade) 	| [![clickalicious open source](https://img.shields.io/badge/clickalicious-open--source-green.svg?style=flat)](https://clickalicious.de/) 	|
|---	|---	|---	|---	|
| [![GitHub release](https://img.shields.io/github/release/clickalicious/rng.svg?style=flat)](https://github.com/clickalicious/rng/releases) 	| [![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://opensource.org/licenses/MIT)  	| [![Issue Stats](https://img.shields.io/issuestats/i/github/clickalicious/rng.svg)](https://github.com/clickalicious/rng/issues) 	| [![Dependency Status](https://dependencyci.com/github/clickalicious/rng/badge)](https://dependencyci.com/github/clickalicious/rng)  	|


## Table of Contents

- [Features](#features)
- [Examples](#examples)
- [Requirements](#requirements)
- [Philosophy](#philosophy)
- [Versioning](#versioning)
- [Roadmap](#roadmap)
- [Security-Issues](#security-issues)
- [License »](LICENSE)


## Features

 - High performance (developed using a profiler)
 - Lightweight and high-quality codebase (following PSR standards e.g. `PSR-1,2,4`)
 - Secure `PRNG` implementation (64-Bit support)
 - OOP facade to PHP core functionality
 - PHP > `5.6` up to `7.2` & `HHVM` ready
 - Stable, clean + well documented code
 - Unit-tested with a good coverage


## Examples

Generate `random number` between 1 and 10 using `OPEN_SSL` random bytes (library default):
```php
$generator = new Rng\Generator();
$number    = $generator->generate(1, 10);
echo $number;
```

Generate `random number` between 1 and 10 using `PHP_MERSENNE_TWISTER` random bytes:
```php
$generator = new Rng\Generator(Rng\Generator::MODE_PHP_MERSENNE_TWISTER);
$number    = $generator->generate(1, 10);
echo $number;
```

Generate `16 random bytes` using `MODE_OPEN_SSL` random bytes (library default):
```php
$generator = new Rng\Generator();
$bytes     = $generator->getRandomBytes(16);
```

Generate `32 random bytes` using `NATIVE-PHP` random bytes:
```php
$generator = new Rng\Generator();
$bytes     = $generator->getRandomBytes(32);
```


### Visualization

You can create a visualization of randomization (as you can see below but larger size) through [`visual.php` »](visual.php) (the file is located in root).

![Logo of rng](docs/visualization.png)


## Requirements

 - `PHP >= 5.6` (compatible up to version `7.2` as well as `HHVM`)


## Philosophy

This library provides a state of the art `PRNG` (**P**seudo **R**andom **N**umber **G**enerator) implementation to generate secure `Pseudo Random Numbers` with PHP. The generation is either based on `Open SSL` or `MCrypt` or as fallback on PHP's internal functionality. The library also provides a very good `Seed generator` on puplic API. If you are interested in the difference between real and pseduo randomness then you could start at [https://www.random.org/randomness/](https://www.random.org/randomness/ "https://www.random.org/randomness/").

[![Scott Adams](https://www.random.org/analysis/dilbert.jpg)](http://dilbert.com/strip/2001-10-25 "Copyright Universal Uclick / Scott Adams")

`DILBERT © 2001 Scott Adams.`


## Versioning

For a consistent versioning i decided to make use of `Semantic Versioning 2.0.0` http://semver.org. Its easy to understand, very common and known from many other software projects.


## Roadmap

- No open issues.

[![Throughput Graph](https://graphs.waffle.io/clickalicious/rng/throughput.svg)](https://waffle.io/clickalicious/rng/metrics)


## Security Issues

If you encounter a (potential) security issue don't hesitate to get in contact with us `opensource@clickalicious.de` before releasing it to the public. So i get a chance to prepare and release an update before the issue is getting shared. Thank you!


## Participate & Share

... yeah. If you're a code monkey too - maybe we can build a force ;) If you would like to participate in either **Code**, **Comments**, **Documentation**, **Wiki**, **Bug-Reports**, **Unit-Tests**, **Bug-Fixes**, **Feedback** and/or **Critic** then please let me know as well!
<a href="https://twitter.com/intent/tweet?hashtags=&original_referer=http%3A%2F%2Fgithub.com%2F&text=rng%20-%20Random%20number%20generator%20for%20PHP%20%40phpfluesterer%20%23rng%20%23php%20https%3A%2F%2Fgithub.com%2Fclickalicious%2Frng&tw_p=tweetbutton" target="_blank">
  <img src="http://jpillora.com/github-twitter-button/img/tweet.png"></img>
</a>

## Sponsors

Thanks to our sponsors and supporters:

| JetBrains | Navicat |
|---|---|
| <a href="https://www.jetbrains.com/phpstorm/" title="PHP IDE :: JetBrains PhpStorm" target="_blank"><img src="https://resources.jetbrains.com/assets/media/open-graph/jetbrains_250x250.png" height="55"></img></a> | <a href="http://www.navicat.com/" title="Navicat GUI - DB GUI-Admin-Tool for MySQL, MariaDB, SQL Server, SQLite, Oracle & PostgreSQL" target="_blank"><img src="http://upload.wikimedia.org/wikipedia/en/9/90/PremiumSoft_Navicat_Premium_Logo.png" height="55" /></a>  |


###### Copyright
<div>Icons made by <a href="http://www.flaticon.com/authors/roundicons" title="Roundicons">Roundicons</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
