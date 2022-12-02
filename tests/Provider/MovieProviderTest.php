<?php

namespace App\Tests\Provider;

use App\Consumer\OMDbApiConsumer;
use App\Entity\Movie;
use App\Entity\User;
use App\Provider\GenreProvider;
use App\Provider\MovieProvider;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class MovieProviderTest extends TestCase
{
    private OMDbApiConsumer $omdbApiConsumer;
    private OmdbMovieTransformer $omdbMovieTransformer;
    private MovieRepository $movieRepository;
    private GenreProvider $genreProvider;
    private Security $security;
    private MovieProvider $movieProvider;

    protected function setUp(): void
    {
        $this->omdbApiConsumer = $this->createMock(OMDbApiConsumer::class);
        $this->omdbMovieTransformer = $this->createMock(OmdbMovieTransformer::class);
        $this->movieRepository = $this->createMock(MovieRepository::class);
        $this->genreProvider = $this->createMock(GenreProvider::class);
        $this->security = $this->createMock(Security::class);
        $this->movieProvider = new MovieProvider(
            $this->omdbApiConsumer,
            $this->omdbMovieTransformer,
            $this->movieRepository,
            $this->genreProvider,
            $this->security
        );
    }

    public function testGetExistingMovie()
    {
        // Create expected Movie
        $expectedMovieFromDatabase = (new Movie())
            ->setTitle('My title')
        ;

        // Create and configure mocks
        $this->omdbApiConsumer->expects($this->once())->method('consume')->willReturn(['Title' => 'mock title', 'Genre' => 'mock genre']);
        $this->movieRepository->expects($this->once())->method('findOneBy')->with(['title' => 'mock title'])->willReturn($expectedMovieFromDatabase);
        $this->omdbMovieTransformer->expects($this->never())->method('transform');

        // Call method
        $movie = $this->movieProvider->getMovie('my_type', 'my_value');

        // Assert that the provider returns the object returned by Transformer
        $this->assertSame($expectedMovieFromDatabase, $movie);
    }

    public function testGetNotExistingMovie()
    {
        // Create expected Movie
        $expectedMovieFromTransformer = (new Movie())
            ->setTitle('My title')
        ;

        // Create and configure mocks
        $this->omdbApiConsumer->expects($this->once())->method('consume')->willReturn(['Title' => 'mock title', 'Genre' => 'mock genre']);
        $this->movieRepository->expects($this->once())->method('findOneBy')->with(['title' => 'mock title'])->willReturn(null);
        $this->omdbMovieTransformer->expects($this->once())->method('transform')->willReturn($expectedMovieFromTransformer);

        // Call method
        $movie = $this->movieProvider->getMovie('my_type', 'my_value');

        // Assert that the provider returns the object returned by Transformer
        $this->assertSame($expectedMovieFromTransformer, $movie);
    }

    public function testGetNotExistingWithUserMovie()
    {
        // Create expected Movie
        $expectedMovieFromTransformer = (new Movie())
            ->setTitle('My title')
        ;
        $expectedUser = new User();

        // Create and configure mocks
        $this->omdbApiConsumer->expects($this->once())->method('consume')->willReturn(['Title' => 'mock title', 'Genre' => 'mock genre']);
        $this->movieRepository->expects($this->once())->method('findOneBy')->with(['title' => 'mock title'])->willReturn(null);
        $this->omdbMovieTransformer->expects($this->once())->method('transform')->willReturn($expectedMovieFromTransformer);
        $this->security->method('getUser')->willReturn($expectedUser);

        // Call method
        $movie = $this->movieProvider->getMovie('my_type', 'my_value');

        // Assert that the provider returns the object returned by Transformer
        $this->assertSame($expectedMovieFromTransformer, $movie);
        $this->assertSame($expectedMovieFromTransformer->getCreatedBy(), $expectedUser);
    }
}
