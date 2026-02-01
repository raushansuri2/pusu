<?php
namespace App\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Http\Exception\TooManyRequestsException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class UserRateLimiting implements MiddlewareInterface
{
    // Define the maximum number of requests allowed
    private const MAX_REQUESTS = 5;
    // Define the time window in seconds
    private const TIME_WINDOW = 60; // secs

    // Store request counts and timestamps
    private $requestCounts = [];
    private $requestTimestamps = [];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userIdentifier = $this->request->getHeaderLine('User-Agent'); // You can also use user ID if authenticated

        // Initialize request count and timestamp if not set
        if (!isset($this->requestCounts[$userIdentifier])) {
            $this->requestCounts[$userIdentifier] = 0;
            $this->requestTimestamps[$userIdentifier] = time();
        }

        // Check if the time window has expired
        if (time() - $this->requestTimestamps[$userIdentifier] > self::TIME_WINDOW) {
            // Reset the count and timestamp
            $this->requestCounts[$userIdentifier] = 1;
            $this->requestTimestamps[$userIdentifier] = time();
        } else {
            // Increment the request count
            $this->requestCounts[$userIdentifier]++;
        }

        // Check if the request count exceeds the limit
        if ($this->requestCounts[$userIdentifier] > self::MAX_REQUESTS) {
            throw new TooManyRequestsException('You have exceeded the maximum number of requests. Please try again later.');
        }

        // Proceed to the next middleware or request handler
        return $handler->handle($request);
    }
}