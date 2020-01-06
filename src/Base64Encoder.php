<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule;

class Base64Encoder
{
    public function encode(string $file_path): string
    {
        return chunk_split(base64_encode(file_get_contents($file_path)));
    }
}