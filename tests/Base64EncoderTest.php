<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Tests;

use Nalognl\MegaplanModule\Base64Encoder;

class Base64EncoderTest extends TestCase
{
    /** @test */
    public function getEncoded_returns_encoded_string(): void
    {
        $file_path = NNND_STORAGE . 'tests/base64_before';
        $expect = file_get_contents(NNND_STORAGE . 'tests/base64_after');

        $this->assertSame($expect, (new Base64Encoder)->encode($file_path));
    }
}
