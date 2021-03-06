<?php

namespace App\Http\Controllers;

use App\Http\Requests\SongRequest;
use App\Models\Song;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Song::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SongRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SongRequest $request)
    {
        $song = Song::create([
            'file' => Storage::disk('songs')->putFile('', $request->file('file')),
        ]);

        return response()->json($song, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Song $song)
    {
        return response()->json($song);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function destroy(Song $song)
    {
        $song->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
