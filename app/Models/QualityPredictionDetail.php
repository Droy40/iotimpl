<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityPredictionDetail extends Model
{
    protected $table = 'quality_prediction_details';
    protected $primaryKey = 'id_quality_prediction_detail';
    protected $fillable = ['id_quality_prediction', 'tagName', 'probability'];
    public $timestamps = false;

    public function quality()
    {
        return $this->belongsTo(QualityPrediction::class, 'id_quality_prediction','id_quality_prediction');
    }
}
