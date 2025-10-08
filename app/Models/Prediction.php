<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $table = 'predictions';
    // primary key is the default 'id'
    protected $fillable = ['idphotos', 'created', 'project'];
    public $timestamps = false;

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'idphotos', 'idphotos');
    }

    public function details()
    {
        // Order details by probability descending (largest first)
        return $this->hasMany(PredictionDetail::class, 'prediction_id', 'id')->orderByDesc('probability');
    }
}
