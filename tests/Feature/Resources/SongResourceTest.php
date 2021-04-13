<?php

use App\Models\Song;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('can list the songs', function () {
    $count = 5;

    Song::factory($count)->create();

    $this->getJson(route('songs.index'))
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount($count);
});

it('can store a song', function () {
    Storage::fake('songs');

    $response = $this->postJson(route('songs.store'), [
        'file' => UploadedFile::fake()->create('song.mp3', 1000, 'audio/mpeg')
    ]);

    $song = Song::first();

    $response
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'id' => $song->id,
        ]);

    Storage::disk('songs')->assertExists($song->file);
});

it("returns an error if you store a song without a file", function () {
    $this->postJson(route('songs.store'))
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('can show a song', function () {
    $song = Song::factory()->create();

    $this->getJson(route('songs.show', $song))
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'id' => $song->id,
        ]);
});

it('returns an error if you show a song that does not exist', function () {
    $this->getJson(route('songs.destroy', 1))
        ->assertStatus(Response::HTTP_NOT_FOUND);
});

it('can delete a song', function () {
    $this->deleteJson(route('songs.destroy', Song::factory()->create()))
        ->assertStatus(Response::HTTP_NO_CONTENT)
        ->assertNoContent();
});

it('returns an error if you delete a song that does not exist', function () {
    $this->deleteJson(route('songs.destroy', 1))
        ->assertStatus(Response::HTTP_NOT_FOUND);
});

it('can spread a song\'s file', function () {
    Storage::fake('songs');

    $this->postJson(route('songs.store'), [
        'file' => UploadedFile::fake()->create('song.mp3', 1000, 'audio/mpeg')
    ]);

    $song = Song::first();

    Storage::disk('songs')->assertExists($song->file);

    $this->get(route('songs.file', $song))
        ->assertStatus(Response::HTTP_OK);
});
