<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Prediction;
use App\Models\PredictionDetail;
use App\Services\PredictionsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PhotoController extends Controller
{
    protected PredictionsService $predictionsService;

    public function __construct(PredictionsService $predictionsService)
    {
        $this->predictionsService = $predictionsService;
    }

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
     * Store a newly created resource in storage. Handles form submission from the create view.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:8192',
        ]);

        if (! $request->hasFile('image')) {
            return redirect()->back()->withErrors(['image' => 'Please upload an image!']);
        }

        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        // Simpan ke folder public/photos
        $image->move(public_path('photos'), $imageName);

        // Public URL for clients
        $publicUrl = url('photos/' . $imageName);
        // Local filesystem path for reading bytes
        $localPath = public_path('photos/' . $imageName);

        // Simpan data photo awal
        $photo = new Photo();
        $photo->location = $publicUrl;
        $photo->save();

        try {
            $predictionApiResult = $this->predictionsService->predict($localPath);

            if (!is_array($predictionApiResult) || empty($predictionApiResult['predictions'] ?? null)) {
                throw new \RuntimeException('Invalid prediction response from external service');
            }

            DB::transaction(function () use ($photo, $predictionApiResult) {
                $predictionResult = new Prediction();
                $createdValue = $predictionApiResult['created'] ?? null;
                try {
                    $predictionResult->created = $createdValue ? Carbon::parse($createdValue)->format('Y-m-d H:i:s') : Carbon::now()->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $predictionResult->created = Carbon::now()->format('Y-m-d H:i:s');
                }

                $predictionResult->project = $predictionApiResult['project'] ?? null;

                $photo->predictions()->save($predictionResult);

                $details = $predictionApiResult['predictions'] ?? [];
                foreach ($details as $result) {
                    if (!isset($result['tagName']) || !isset($result['probability'])) {
                        continue;
                    }
                    $predictionDetail = new PredictionDetail();
                    $predictionDetail->tagName = $result['tagName'];
                    $predictionDetail->probability = (float) $result['probability'];
                    $predictionResult->details()->save($predictionDetail);
                }
            });

            $photo->load('predictions.details');

            // Redirect to the photo show page for web clients
            return redirect()->route('photo.show', $photo->idphotos)->with('message', 'File uploaded and predicted successfully.');

        } catch (\Exception $e) {
            Log::error('Prediction error: ' . $e->getMessage(), ['exception' => $e]);

            try {
                if ($photo->exists) {
                    $photo->delete();
                }
            } catch (\Exception $ex) {
                Log::warning('Failed to delete photo record during cleanup: ' . $ex->getMessage());
            }

            if (file_exists($localPath)) {
                @unlink($localPath);
            }

            return redirect()->back()->with('message', 'Prediction failed: ' . $e->getMessage());
        }
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
