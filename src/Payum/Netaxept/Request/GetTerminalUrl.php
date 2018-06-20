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

namespace FDM\Payum\Netaxept\Request;

use GuzzleHttp\Psr7\Uri;

class GetTerminalUrl
{
    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var Uri
     */
    protected $terminalUrl;

    public function __construct(string $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @return Uri
     */
    public function getTerminalUrl(): Uri
    {
        return $this->terminalUrl;
    }

    /**
     * @param Uri $terminalUrl
     */
    public function setTerminalUrl(Uri $terminalUrl)
    {
        $this->terminalUrl = $terminalUrl;
    }
}
