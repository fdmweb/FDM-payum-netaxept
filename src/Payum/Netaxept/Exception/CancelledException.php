<?php

declare(strict_types=1);

/*
 * This file is part of the Netaxept Payum Gateway package.
 *
 * (c) Andrew Plank
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDM\Payum\Netaxept\Exception;

/**
 * Thrown when the user clicks the "cancel" button in the Netaxept termnial window.
 */
class CancelledException extends \Exception
{
}
