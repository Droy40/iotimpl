<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityPrediction extends Model
{
    protected $table = 'quality_predictions';
    protected $primaryKey = 'id_quality_prediction';
    protected $fillable = ['idphotos', 'created', 'project'];
    public $timestamps = false;

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'idphotos','idphotos');
    }
    public function detail()
    {
        return $this->hasMany(QualityPredictionDetail::class, 'id_quality_prediction','id_quality_prediction');
    }

}
