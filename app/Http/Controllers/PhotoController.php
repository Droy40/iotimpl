<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\QualityPredictionDetail;
use App\Models\QualityPrediction;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photos = Photo::all();
        return view('photo.index', compact( 'photos'));
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
        $photo = Photo::findOrFail($id);
        // Tampilkan data ke view
        return view('photo.show', compact('photo'));
    }

}
