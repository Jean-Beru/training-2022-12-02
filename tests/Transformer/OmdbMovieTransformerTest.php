<?php

namespace App\Tests\Transformer;

use App\Entity\Movie;
use App\Transformer\OmdbMovieTransformer;
use PHPUnit\Framework\TestCase;

class OmdbMovieTransformerTest extends TestCase
{

    public function testTransform()
    {
        $transformer = new OmdbMovieTransformer();
        $result = $transformer->transform([
            'Title' => 'title',
            'Poster' => 'poster',
            'Country' => 'be',
            'Released' => '2022',
            'Year' => '2022',
            'Rated' => '5',
            'imdbID' => '42',
        ]);

//        $expected = (new Movie())
//            ->setTitle('title')
//        ;
//        $this->assertEquals($expected, $result);

        $this->assertSame('title', $result->getTitle());
        $this->assertSame('poster', $result->getPoster());
        $this->assertSame('be', $result->getCountry());
    }

    public function testTransformAlternative()
    {
        $transformer = new OmdbMovieTransformer();
        $result = $transformer->transform([
            'Title' => 'title',
            'Poster' => 'poster',
            'Country' => 'be',
            'Released' => '2022-01-01 00:00:00',
            'Year' => '2022',
            'Rated' => '5',
            'imdbID' => '42',
        ]);

        $expected = (new Movie())
            ->setTitle('title')
            ->setPoster('poster')
            ->setCountry('be')
            ->setReleasedAt(new \DateTimeImmutable('2022-01-01 00:00:00'))
            ->setRated('5')
            ->setImdbID('42')
            ->setPrice('5.0')
        ;

        $this->assertEquals($expected, $result);
    }

    public function testTransformEmptyArray()
    {
        $this->expectExceptionObject(new \InvalidArgumentException('Field "Title" is missing.'));

        $transformer = new OmdbMovieTransformer();
        $transformer->transform([]);
    }
}
