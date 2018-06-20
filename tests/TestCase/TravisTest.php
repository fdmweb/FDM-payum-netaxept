<?php

/*
 * This file is part of the Netaxept API package.
 *
 * (c) Andrew Plank
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\TestCase;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class TravisTest extends TestCase
{
    public function testPlaceholderTestToCheckTravisIsWorking()
    {
        Assert::assertFalse(false);
    }
}