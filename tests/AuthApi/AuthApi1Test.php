<?php

namespace Tests\Unit;

use Exception;
use Nalognl\MegaplanModule\AuthApi\AuthApi1;
use Nalognl\MegaplanModule\Tests\TestCase;

class AuthApi1Test extends TestCase
{
    /** @test */
    public function getRequest_throws_exception_when_authorization_has_not_occurred(): void
    {
        $this->expectException(Exception::class);

        $auth = new AuthApi1;
        $auth->getRequest();
    }
}