<?php

namespace App\Tests\Entity;

use App\Entity\Movie;
use PHPUnit\Framework\TestCase;

class MovieTest extends TestCase
{
    public function testGetTitle()
    {
        $movie = new Movie();
        $movie->setTitle('title');

        $this->assertSame('title', $movie->getTitle());

        return $movie;
    }

    /**
     * @depends testGetTitle
     */
    public function testGetOverridenTitle(Movie $movie)
    {
        $this->assertSame('title', $movie->getTitle());

        $movie->setTitle('new title');

        $this->assertSame('new title', $movie->getTitle());
    }
}
