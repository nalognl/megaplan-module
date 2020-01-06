<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Tests;

use Nalognl\MegaplanModule\Base64Encoder;

class Base64EncoderTest extends TestCase
{
    /** @test */
    public function getEncoded_returns_encoded_string(): void
    {
        $file_path = __DIR__ . '/storage/base64_before';

        $expect = preg_replace("/[\r]/", '', file_get_contents(__DIR__ . '/storage/base64_after'));
        $result = preg_replace("/[\r]/", '', (new Base64Encoder)->encode($file_path));

        $this->assertEquals($expect, $result);
    }
}
