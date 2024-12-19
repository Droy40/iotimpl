<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class AppleQualityPredictionService
{
    public function predict($imgPath)
    {
        $fileContent = file_get_contents($imgPath); // Membaca isi file

        $response = Http::withHeaders([
            'Prediction-Key' => env('CUSTOM_VISIONS_APPLE_QUALITY_KEY'),
            'Content-Type' => 'application/octet-stream',
        ])->withBody($fileContent, 'application/octet-stream')
            ->post(env('CUSTOM_VISIONS_APPLE_QUALITY_URL'));

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch data: ' . $response->body());
    }
}
