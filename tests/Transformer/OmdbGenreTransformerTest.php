<?php

namespace App\Tests\Transformer;

use App\Transformer\OmdbGenreTransformer;
use PHPUnit\Framework\TestCase;

class OmdbGenreTransformerTest extends TestCase
{
    /**
     * @dataProvider provideTransform
     */
    public function testTransform(string $expected, string $value)
    {
        $transformer = new OmdbGenreTransformer();
        $result = $transformer->transform($value);

        $this->assertSame($expected, $result->getName());
    }

    public function provideTransform()
    {
        yield 'already lowercase' => ['drama', 'drama'];
        yield 'partial' => ['drama', 'DramA'];
        yield 'full' => ['drama', 'DRAMA'];
    }
}
