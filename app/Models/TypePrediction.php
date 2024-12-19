<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypePrediction extends Model
{
    protected $table = 'type_predictions';
    protected $primaryKey = 'id_type_prediction';
    protected $fillable = ['idphotos', 'created'];
    public $timestamps = false;

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'idphotos', 'idphotos');
    }
    public function detail()
    {
        return $this->hasMany(TypePredictionDetail::class, 'id_type_prediction','id_type_prediction');
    }
}
