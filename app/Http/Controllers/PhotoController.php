<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photos = Photo::with(['predictions' => function ($q) {
            $q->orderBy('created', 'desc')->with('details');
        }])->orderBy('idphotos', 'desc')->get();

        return view('photo.index', compact('photos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('photo.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $photo = Photo::with(['predictions' => function ($q) {
            $q->orderBy('created', 'desc')->with('details');
        }])->findOrFail($id);

        return view('photo.show', compact('photo'));
    }

}
