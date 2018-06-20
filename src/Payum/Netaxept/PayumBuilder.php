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

namespace FDM\Payum\Netaxept;

use FDM\Payum\Netaxept\Security\GenericTokenFactory;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Core\Security\TokenFactoryInterface;

class PayumBuilder extends \Payum\Core\PayumBuilder
{
    public function buildGenericTokenFactory(TokenFactoryInterface $tokenFactory, array $paths)
    {
        $genericTokenFactory = $this->genericTokenFactory;

        if (is_callable($genericTokenFactory)) {
            $genericTokenFactory = call_user_func($genericTokenFactory, $tokenFactory, $paths);

            if (false == $genericTokenFactory instanceof GenericTokenFactoryInterface) {
                throw new \LogicException('Builder returned invalid instance');
            }
        }

        return $genericTokenFactory ?: new GenericTokenFactory($tokenFactory, $paths);
    }
}
