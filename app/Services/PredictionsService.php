<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class PredictionsService
{
    public function predict($imgPath)
    {
        $fileContent = file_get_contents($imgPath);
        // Use config() instead of env() inside application code
        $predictionUrl = config('services.custom_vision.prediction_url');
        $predictionKey = config('services.custom_vision.prediction_key');

        if (empty($predictionUrl) || empty($predictionKey)) {
            throw new \RuntimeException('Custom Vision configuration is missing. Ensure CUSTOM_VISIONS_PREDICTION_URL and CUSTOM_VISIONS_PREDICTION_KEY are set in your environment and config/services.php has the correct keys.');
        }

        $response = Http::withHeaders([
            'Prediction-Key' => $predictionKey,
            'Content-Type' => 'application/octet-stream',
        ])->timeout(30)
            ->withBody($fileContent, 'application/octet-stream')
            ->post($predictionUrl);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch data: ' . $response->body());
    }
}
