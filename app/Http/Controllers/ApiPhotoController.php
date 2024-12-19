<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\QualityPrediction;
use App\Models\QualityPredictionDetail;
use App\Models\TypePrediction;
use App\Models\TypePredictionDetail;
use App\Services\AppleQualityPredictionService;
use App\Services\BananaQualityPredictionService;
use App\Services\TypePredictionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiPhotoController extends Controller
{
    protected $typePredictionService;
    protected $appleQualityPredictionService;
    protected $bananaQualityPredictionService;

    public function __construct(TypePredictionService $typePredictionService,
                                AppleQualityPredictionService $appleQualityPredictionService,
                                BananaQualityPredictionService $bananaQualityPredictionService)
    {
        $this->typePredictionService = $typePredictionService;
        $this->appleQualityPredictionService = $appleQualityPredictionService;
        $this->bananaQualityPredictionService = $bananaQualityPredictionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return csrf_token();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:8192',
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension(); // Generate nama unik

            // Simpan ke folder public/photos
            $image->move(public_path('photos'), $imageName);

            // Simpan URL gambar
            $imagePath = url('photos/' . $imageName);
            // Simpan data produk ke database
            $photo = new Photo();
            $photo->location = $imagePath;
            $photo->save();

            //Prediksi Type
            $typeResult = $this->typePredictionService->predict($imagePath);

            //simpan typePrediction ke db
            $typePrediction = new TypePrediction();
            $typePrediction->created = Carbon::parse($typeResult['created'])->format('Y-m-d H:i:s');
            $photo->typePrediction()->save($typePrediction);

            //simpan hasil tag dan prob nya ke db
            foreach ($typeResult['predictions'] as $result) {
                $typePredictionDetail = new TypePredictionDetail();
                $typePredictionDetail->tagName = $result['tagName'];
                $typePredictionDetail->probability = $result['probability'];
                $typePrediction->detail()->save($typePredictionDetail);
            }
            $highestTypePrediction = $typePrediction->detail()->orderBy('probability', 'desc')->first();
            //jika prediksi apple tertinggi
            if($highestTypePrediction->tagName == 'apple'){
                $qualityResult = $this->appleQualityPredictionService->predict($imagePath);
            }
            //jika prediksi banana tertinggi
            else{
                $qualityResult = $this->bananaQualityPredictionService->predict($imagePath);
            }

            $qualityPrediction = new QualityPrediction();
            $qualityPrediction->created = Carbon::parse($qualityResult['created'])->format('Y-m-d H:i:s');
            $qualityPrediction->project = $qualityResult['project'];
            $photo->qualityPrediction()->save($qualityPrediction);
            foreach ($qualityResult['predictions'] as $result) {
                $qualityPredictionDetail = new QualityPredictionDetail();
                $qualityPredictionDetail->tagName = $result['tagName'];
                $qualityPredictionDetail->probability = $result['probability'];
                $qualityPrediction->detail()->save($qualityPredictionDetail);
                unset($result['tagName']);
            }

            // Kembalikan respon JSON
            return response()->json([
                'message' => 'Successfully predicted the image!',
                'result' => [
                    'type'=> $highestTypePrediction->tagName,
                    'probability' => $highestTypePrediction->probability,
                    'quality' => array_map(function ($prediction)
                    {
                        unset($prediction['tagId']);
                        return $prediction;
                    }, $qualityResult['predictions'])
                ]
            ], 201);
        }

        return response()->json([
            'message' => 'Please upload an image!'
        ],400);

    }
//
//    /**
//     * Display the specified resource.
//     */
//    public function show(string $id)
//    {
//    }
//
//    /**
//     * Update the specified resource in storage.
//     */
//    public function update(Request $request, string $id)
//    {
//        //
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     */
//    public function destroy(string $id)
//    {
//        //
//    }
}
