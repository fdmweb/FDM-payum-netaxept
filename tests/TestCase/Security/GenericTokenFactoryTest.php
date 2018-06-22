<?php

/*
 * This file is part of the Netaxept Payum Gateway package.
 *
 * (c) Andrew Plank
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\TestCase\Model;

use FDM\Payum\Netaxept\Security\GenericTokenFactory;
use Payum\Core\Model\Token;
use Payum\Core\Security\TokenFactoryInterface;
use PHPUnit\Framework\TestCase;

class GenericTokenFactoryTest extends TestCase
{
    public function testFactory()
    {
        $tokenFac = $this->createMock(TokenFactoryInterface::class);
        $afterToken = new Token();
        $afterToken->setTargetUrl('http://localhost/thanks.php');
        $tokenFac->expects($this->at(0))->method('createToken')->willReturn($afterToken);
        $authAndCapToken = new Token();
        $authAndCapToken->setTargetUrl('http://localhost/authandcap.php');
        $authAndCapToken->setAfterUrl($afterToken->getTargetUrl());
        $tokenFac->expects($this->at(1))->method('createToken')->willReturn($authAndCapToken);

        $genericFac = new GenericTokenFactory($tokenFac, ['auth_and_capture' => 'http://localhost/authandcap.php']);

        $token = $genericFac->createAuthAndCaptureToken('netaxept', [], 'http://localhost/thanks.php');
        self::assertEquals('http://localhost/authandcap.php', $token->getTargetUrl());
        self::assertEquals('http://localhost/thanks.php', $token->getAfterUrl());
    }
}
