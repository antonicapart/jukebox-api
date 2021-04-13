<?php

use App\Models\Song;

beforeEach(function () {
    $this->song = Song::factory()->create();
});

it('must be soft deleted', function () {
    $this->song->delete();

    $this->assertSoftDeleted($this->song);
});
