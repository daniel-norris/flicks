<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Movie;

class MovieTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBasicMovieFactory()
    {
        $movie = factory(Movie::class, 1)->create();

        $this->assertDatabaseCount('movies', 1);
    }
}
