<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Support\Facades\Storage;

class SongFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Song  $song
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function __invoke(Song $song)
    {
        return Storage::disk('songs')->download($song->file);
    }
}
