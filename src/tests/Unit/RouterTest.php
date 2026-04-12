<?php

namespace Tests\Unit;

use App\Core\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        parent::setUp();
        $this->router = new Router();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_POST = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
        $_POST = [];
    }

    // ── BASIC ROUTING ───────────────────────────────

    public function testGetRouteMatchesExactPath(): void
    {
        $this->router->get('/home', fn() => 'home page');
        $_SERVER['REQUEST_URI'] = '/home';

        $result = $this->router->resolve();

        $this->assertSame('home page', $result);
    }

    public function testPostRouteMatches(): void
    {
        $this->router->post('/submit', fn() => 'submitted');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/submit';

        $result = $this->router->resolve();

        $this->assertSame('submitted', $result);
    }

    public function testPutRouteMatches(): void
    {
        $this->router->put('/update', fn() => 'updated');
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['REQUEST_URI'] = '/update';

        $result = $this->router->resolve();

        $this->assertSame('updated', $result);
    }

    public function testDeleteRouteMatches(): void
    {
        $this->router->delete('/remove', fn() => 'removed');
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SERVER['REQUEST_URI'] = '/remove';

        $result = $this->router->resolve();

        $this->assertSame('removed', $result);
    }

    // ── DYNAMIC PARAMETERS ──────────────────────────

    public function testColonParameterExtracted(): void
    {
        $this->router->get('/users/:id', fn($id) => "user-$id");
        $_SERVER['REQUEST_URI'] = '/users/42';

        $result = $this->router->resolve();

        $this->assertSame('user-42', $result);
    }

    public function testAlphanumericParameterSupported(): void
    {
        $this->router->get('/games/:id', fn($id) => "game-$id");
        $_SERVER['REQUEST_URI'] = '/games/abc123';

        $result = $this->router->resolve();

        $this->assertSame('game-abc123', $result);
    }

    // ── QUERY STRING ────────────────────────────────

    public function testQueryStringIsStripped(): void
    {
        $this->router->get('/search', fn() => 'results');
        $_SERVER['REQUEST_URI'] = '/search?q=zelda&page=2';

        $result = $this->router->resolve();

        $this->assertSame('results', $result);
    }

    // ── METHOD SPOOFING ─────────────────────────────

    public function testMethodSpoofingViaPostField(): void
    {
        $this->router->put('/item', fn() => 'put-ok');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/item';
        $_POST['_method'] = 'PUT';

        $result = $this->router->resolve();

        $this->assertSame('put-ok', $result);
    }

    public function testDeleteMethodSpoofing(): void
    {
        $this->router->delete('/item', fn() => 'delete-ok');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/item';
        $_POST['_method'] = 'DELETE';

        $result = $this->router->resolve();

        $this->assertSame('delete-ok', $result);
    }

    // ── 404 HANDLING ────────────────────────────────

    public function testReturns404ForUnknownRoute(): void
    {
        $_SERVER['REQUEST_URI'] = '/nonexistent';

        $result = $this->router->resolve();

        $this->assertSame(404, http_response_code());
        $this->assertStringContainsString('404', $result);
    }

    public function testReturns404JsonForApiRoute(): void
    {
        $_SERVER['REQUEST_URI'] = '/api/unknown';

        $result = $this->router->resolve();

        $this->assertSame(404, http_response_code());
        $decoded = json_decode($result, true);
        $this->assertSame('Route not found', $decoded['error']);
    }

    // ── CALLABLE TYPES ──────────────────────────────

    public function testClosureCallback(): void
    {
        $called = false;
        $this->router->get('/test', function () use (&$called) {
            $called = true;
            return 'closure';
        });
        $_SERVER['REQUEST_URI'] = '/test';

        $result = $this->router->resolve();

        $this->assertTrue($called);
        $this->assertSame('closure', $result);
    }
}
