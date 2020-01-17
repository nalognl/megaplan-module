<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Tests;

use Andrew\Proxy;
use Exception;
use Nalognl\MegaplanModule\AuthApi\AuthApi1;
use Nalognl\MegaplanModule\Http\RequestMegaplan\RequestMegaplan;

final class RequestMegaplanTest extends TestCase
{
    /** @test */
    public function throwIfError_throws_exception_if_status_code_is_not_200(): void
    {
        $this->expectException(Exception::class);

        $subject = new RequestMegaplan($this->createMock(AuthApi1::class));
        $response = (object) ['status' => (object) ['code' => 300]];

        (new Proxy($subject))->throwIfError($response, '');
    }

    /** @test */
    public function throwIfError_throws_exception_if_status_code_is_not_ok(): void
    {
        $this->expectException(Exception::class);

        $subject = new RequestMegaplan($this->createMock(AuthApi1::class));
        $response = (object) ['meta' => (object) ['status' => 'error']];

        (new Proxy($subject))->throwIfError($response, '');
    }

    /** @test */
    public function throwIfError_throws_with_error_message_that_contains_status_error_from_response(): void
    {
        $this->expectExceptionMessageRegExp('/^Prefix of the exception message/');
        $this->expectExceptionMessageRegExp('/Error message is here$/');

        $subject = new RequestMegaplan($this->createMock(AuthApi1::class));
        $response = (object) ['status' => (object) [
            'code' => 500,
            'message' => 'Error message is here',
        ]];

        $message = 'Prefix of the exception message';

        (new Proxy($subject))->throwIfError($response, $message);
    }

    /** @test */
    public function throwIfError_throws_with_error_message_that_contains_meta_error_from_response(): void
    {
        $errors = ['message' => 'Error message is here'];
        $encoded = json_encode($errors, JSON_UNESCAPED_UNICODE);

        $this->expectExceptionMessageRegExp('/^Prefix of the exception message/');
        $this->expectExceptionMessageRegExp(sprintf("/%s$/", $encoded));

        $subject = new RequestMegaplan($this->createMock(AuthApi1::class));
        $response = (object) ['meta' => (object) [
            'code' => 500,
            'errors' => $errors,
        ]];

        $message = 'Prefix of the exception message';

        (new Proxy($subject))->throwIfError($response, $message);
    }

    /** @test */
    public function throwIfError_not_throws_if_status_200(): void
    {
        $subject = new RequestMegaplan($this->createMock(AuthApi1::class));
        $response = (object) ['status' => (object) ['code' => 200]];

        try {
            (new Proxy($subject))->throwIfError($response, '');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail('Exception should not be thrown if status code is 200');
        }
    }

    /** @test */
    public function throwIfError_not_throws_if_status_ok(): void
    {
        $subject = new RequestMegaplan($this->createMock(AuthApi1::class));
        $response = (object) ['meta' => (object) ['status' => 'ok']];

        try {
            (new Proxy($subject))->throwIfError($response, '');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail('Exception should not be thrown if status code is 200');
        }
    }

    /** @test */
    public function getEvnOrThrow_throws_exception_if_env_has_not_been_found(): void
    {
        $this->expectException(Exception::class);

        $subject = new RequestMegaplan($this->createMock(AuthApi1::class));
        (new Proxy($subject))->getEnvOrThrow('');
    }
}