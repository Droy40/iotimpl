<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypePredictionDetail extends Model
{
    protected $table = 'type_prediction_details';
    protected $primaryKey = 'id_type_prediction_detail';
    protected $fillable = ['id_type_prediction', 'tagName', 'probability'];
    public $timestamps = false;

    public function typePrediction()
    {
        return $this->belongsTo(TypePrediction::class, 'id_type_prediction','id_type_prediction');
    }
}
