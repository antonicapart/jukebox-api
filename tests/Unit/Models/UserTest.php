<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('must be soft deleted', function () {
    $this->user->delete();

    $this->assertSoftDeleted($this->user);
});
