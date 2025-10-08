<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PredictionDetail extends Model
{
    protected $table = 'prediction_details';
    // primary key is default 'id'
    protected $fillable = ['prediction_id', 'tagName', 'probability'];
    public $timestamps = false;

    protected $casts = [
        'probability' => 'float',
        'tagName' => 'string',
    ];

    public function prediction()
    {
        // Each detail belongs to a Prediction
        return $this->belongsTo(Prediction::class, 'prediction_id', 'id');
    }
}
