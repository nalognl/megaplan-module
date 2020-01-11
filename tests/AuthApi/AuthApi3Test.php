<?php

namespace Tests\Unit;

use Exception;
use Nalognl\MegaplanModule\AuthApi\AuthApi3;
use Nalognl\MegaplanModule\Tests\TestCase;

class AuthApi3Test extends TestCase
{
    /** @test */
    public function getRequest_throws_exception_when_access_token_is_null(): void
    {
        $this->expectException(Exception::class);

        $auth = new AuthApi3;
        $auth->getRequest();
    }
}