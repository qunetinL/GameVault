<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use App\Helpers\CsrfHelper;

class CsrfHelperTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testGenerateTokenCreatesTokenInSession(): void
    {
        $token = CsrfHelper::generateToken();

        $this->assertNotEmpty($token);
        $this->assertSame(64, strlen($token));
        $this->assertTrue(ctype_xdigit($token));
        $this->assertSame($token, $_SESSION['csrf_token']);
    }

    public function testGenerateTokenReturnsSameTokenOnSubsequentCalls(): void
    {
        $token1 = CsrfHelper::generateToken();
        $token2 = CsrfHelper::generateToken();

        $this->assertSame($token1, $token2);
    }

    public function testGetTokenReturnsExistingToken(): void
    {
        $_SESSION['csrf_token'] = 'my_custom_token_value';

        $this->assertSame('my_custom_token_value', CsrfHelper::getToken());
    }

    public function testGetTokenGeneratesTokenIfMissing(): void
    {
        $token = CsrfHelper::getToken();

        $this->assertNotEmpty($token);
        $this->assertSame(64, strlen($token));
    }

    public function testVerifyTokenReturnsTrueForValidToken(): void
    {
        $token = CsrfHelper::generateToken();

        $this->assertTrue(CsrfHelper::verifyToken($token));
    }

    public function testVerifyTokenReturnsFalseForInvalidToken(): void
    {
        CsrfHelper::generateToken();

        $this->assertFalse(CsrfHelper::verifyToken('wrong_token'));
    }

    public function testVerifyTokenReturnsFalseForEmptyString(): void
    {
        CsrfHelper::generateToken();

        $this->assertFalse(CsrfHelper::verifyToken(''));
    }

    public function testVerifyTokenReturnsFalseForNull(): void
    {
        CsrfHelper::generateToken();

        $this->assertFalse(CsrfHelper::verifyToken(null));
    }

    public function testInsertFieldOutputsHiddenInput(): void
    {
        $token = CsrfHelper::generateToken();

        ob_start();
        CsrfHelper::insertField();
        $output = ob_get_clean();

        $this->assertStringContainsString('type="hidden"', $output);
        $this->assertStringContainsString('name="csrf_token"', $output);
        $this->assertStringContainsString('value="' . $token . '"', $output);
    }
}
