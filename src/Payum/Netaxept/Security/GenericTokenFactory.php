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

namespace FDM\Payum\Netaxept\Security;

use Payum\Core\Security\GenericTokenFactory as BaseFactory;

class GenericTokenFactory extends BaseFactory
{
    /**
     * {@inheritdoc}
     */
    public function createAuthAndCaptureToken($gatewayName, $model, $afterPath, array $afterParameters = [])
    {
        $capturePath = $this->getPath('auth_and_capture');

        $afterToken = $this->createToken($gatewayName, $model, $afterPath, $afterParameters);

        return $this->createToken(
            $gatewayName,
            $model,
            $capturePath,
            [],
            $afterToken->getTargetUrl()
        );
    }
}
