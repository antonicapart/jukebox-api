<?php

namespace Tests\Feature\Resources;

use App\Models\Song;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SongResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_songs_can_be_listed()
    {
        $count = 5;

        Song::factory($count)->create();

        $this->getJson(route('songs.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($count);
    }

    public function test_a_song_can_be_stored()
    {
        Storage::fake('songs');

        $response = $this->postJson(route('songs.store'), [
            'file' => UploadedFile::fake()->create('song.mp3', 1000, 'audio/mpeg')
        ]);

        $song = Song::first();

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'id'   => $song->id,
            ]);

        Storage::disk('songs')->assertExists($song->file);
    }

    public function test_storing_a_song_without_file_must_return_an_error()
    {
        $this->postJson(route('songs.store'))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_a_song_can_be_showed()
    {
        $song = Song::factory()->create();

        $this->getJson(route('songs.show', $song))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'id' => $song->id,
            ]);
    }

    public function test_showing_a_song_that_does_not_exist_must_return_an_error()
    {
        $this->getJson(route('songs.destroy', 1))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_a_song_can_be_deleted()
    {
        $song = Song::factory()->create();

        $this->deleteJson(route('songs.destroy', $song))
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent();
    }

    public function test_deleting_a_song_that_does_not_exist_must_return_an_error()
    {
        $this->deleteJson(route('songs.destroy', 1))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_a_song_can_spread_its_file()
    {
        Storage::fake('songs');

        $this->postJson(route('songs.store'), [
            'file' => UploadedFile::fake()->create('song.mp3', 1000, 'audio/mpeg')
        ]);

        $song = Song::first();

        Storage::disk('songs')->assertExists($song->file);

        $this->get(route('songs.file', $song))
            ->assertStatus(Response::HTTP_OK);
    }
}
