<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';
    protected $primaryKey = 'idphotos';
    protected $fillable = ['location'];

    public function typePrediction()
    {
        return $this->hasOne(TypePrediction::class, 'idphotos');
    }
    public function qualityPrediction()
    {
        return $this->hasOne(QualityPrediction::class, 'idphotos');
    }
}
