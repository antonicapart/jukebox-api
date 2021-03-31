<?php

namespace Tests\Unit\Models;

use App\Models\Song;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SongTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a fresh song instance between each test.
     *
     * @var \App\Models\Song
     */
    protected $song;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->song = Song::factory()->create();
    }

    public function test_it_must_be_soft_deleted()
    {
        $this->song->delete();

        $this->assertSoftDeleted($this->song);
    }
}
