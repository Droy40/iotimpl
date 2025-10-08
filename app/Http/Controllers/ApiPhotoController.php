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

class ApiPhotoController extends Controller
{
    public function __construct(protected PredictionsService $predictionsService)
    {
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json(['failed'], 400);
        }

        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension(); // Generate nama unik

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
            // Call external prediction service with local file path (safer & faster than URL)
            $predictionApiResult = $this->predictionsService->predict($localPath);

            // Validate response shape
            if (!is_array($predictionApiResult) || empty($predictionApiResult['predictions'] ?? null)) {
                throw new \RuntimeException('Invalid prediction response from external service');
            }

            // Use DB transaction to ensure prediction + details are stored atomically
            DB::transaction(function () use ($photo, $predictionApiResult) {
                $predictionResult = new Prediction();
                $createdValue = $predictionApiResult['created'] ?? null;
                try {
                    $predictionResult->created = $createdValue ? Carbon::parse($createdValue)->format('Y-m-d H:i:s') : Carbon::now()->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    // fallback to now if parsing fails
                    $predictionResult->created = Carbon::now()->format('Y-m-d H:i:s');
                }

                $predictionResult->project = $predictionApiResult['project'] ?? null;

                $photo->predictions()->save($predictionResult);

                $details = $predictionApiResult['predictions'] ?? [];
                foreach ($details as $result) {
                    // guard keys
                    if (!isset($result['tagName']) || !isset($result['probability'])) {
                        continue; // skip malformed entries
                    }
                    $predictionDetail = new PredictionDetail();
                    $predictionDetail->tagName = $result['tagName'];
                    $predictionDetail->probability = (float) $result['probability'];
                    $predictionResult->details()->save($predictionDetail);
                }
            });

            // Reload relations to include them in the response
            $photo->load('predictions.details');

            return response()->json([
                'message' => 'Successfully predicted the image!',
                'result' => $photo
            ], 201);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Prediction error: ' . $e->getMessage(), ['exception' => $e]);

            // cleanup: remove the photo record and file if they exist
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

            return response()->json([
                'message' => 'Prediction failed: ' . $e->getMessage()
            ], 500);
        }
    }

}
