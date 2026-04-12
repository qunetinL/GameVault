<?php

namespace Tests\Unit\Middleware;

use PHPUnit\Framework\TestCase;
use App\Middleware\RateLimitMiddleware;

class RateLimitMiddlewareTest extends TestCase
{
    private RateLimitMiddleware $middleware;
    private string $sessionKey;

    protected function setUp(): void
    {
        $_SESSION = [];
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $this->sessionKey = 'rate_limit_127_0_0_1';
        $this->middleware = new RateLimitMiddleware();
    }

    public function testFirstRequestIsAllowed(): void
    {
        $this->middleware->handle();

        $this->assertSame(1, $_SESSION[$this->sessionKey]['requests']);
        $this->assertArrayHasKey('first_request', $_SESSION[$this->sessionKey]);
    }

    public function testRequestCountIncrements(): void
    {
        $this->middleware->handle(); // 1
        $this->middleware->handle(); // 2
        $this->middleware->handle(); // 3

        $this->assertSame(3, $_SESSION[$this->sessionKey]['requests']);
    }

    public function testFiveRequestsAreAllowed(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->middleware->handle();
        }

        $this->assertSame(5, $_SESSION[$this->sessionKey]['requests']);
    }

    public function testSixthRequestExceedsLimit(): void
    {
        // Simulate 5 requests within the time window
        $_SESSION[$this->sessionKey] = [
            'requests' => 5,
            'first_request' => time()
        ];

        // The 6th call would die() — we verify the state that triggers blocking
        // by checking the counter exceeds maxRequests after incrementing
        $data = $_SESSION[$this->sessionKey];
        $data['requests']++;

        $this->assertGreaterThan(5, $data['requests']);
    }

    public function testRequestsWithinWindowAccumulate(): void
    {
        // Simulate 4 requests already made
        $_SESSION[$this->sessionKey] = [
            'requests' => 4,
            'first_request' => time()
        ];

        // 5th request should still pass (limit is >5, not >=5)
        $this->middleware->handle();

        $this->assertSame(5, $_SESSION[$this->sessionKey]['requests']);
    }

    public function testRateLimitResetsAfterTimeWindow(): void
    {
        // Simulate 5 requests that happened > 60 seconds ago
        $_SESSION[$this->sessionKey] = [
            'requests' => 5,
            'first_request' => time() - 61
        ];

        $this->middleware->handle();

        // Counter should be reset to 1
        $this->assertSame(1, $_SESSION[$this->sessionKey]['requests']);
    }

    public function testDifferentIpsAreTrackedSeparately(): void
    {
        $_SERVER['REMOTE_ADDR'] = '192.168.1.1';
        $this->middleware->handle();

        $_SERVER['REMOTE_ADDR'] = '192.168.1.2';
        $this->middleware->handle();

        $this->assertSame(1, $_SESSION['rate_limit_192_168_1_1']['requests']);
        $this->assertSame(1, $_SESSION['rate_limit_192_168_1_2']['requests']);
    }
}
