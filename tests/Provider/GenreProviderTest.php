<?php

namespace App\Tests\Provider;

use App\Entity\Genre;
use App\Provider\GenreProvider;
use App\Repository\GenreRepository;
use App\Transformer\OmdbGenreTransformer;
use PHPUnit\Framework\TestCase;

class GenreProviderTest extends TestCase
{
    public function testGetGenresFromString()
    {
        $transformer = $this->createMock(OmdbGenreTransformer::class);
        $transformer->expects($this->never())->method('transform');

        $repository = $this->createMock(GenreRepository::class);
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Action'])
            ->willReturn((new Genre())->setName('Action 2'))
        ;

        $provider = new GenreProvider($transformer, $repository);
        $genre = iterator_to_array($provider->getGenresFromString('Action'));

        $this->assertSame('Action 2', $genre[0]->getName());
    }
}
