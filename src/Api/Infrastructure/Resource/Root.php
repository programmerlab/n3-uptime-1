<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Api\Infrastructure\Resource;

use Acquia\N3\Framework\Application\Resource\ResponseFactory;
use Acquia\N3\Framework\Infrastructure\Resource\AbstractResource;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Provides endpoints for "/".
 */
class Root extends AbstractResource
{
    /**
     * Retrieves the status of the application.
     *
     * @param ServerRequestInterface $request
     *   The incoming request.
     *
     * @return ResponseInterface
     *   A PSR-7-compliant response.
     *
     */
    public function get(ServerRequestInterface $request)
    {
        return ResponseFactory::createJson([
            'status' => 'ok',
        ]);
    }
}
