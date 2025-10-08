<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';
    protected $primaryKey = 'idphotos';
    protected $fillable = ['location'];

    // A photo can have many predictions
    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'idphotos', 'idphotos');
    }

    // Convenience relation to get the latest prediction
    public function latestPrediction()
    {
        // order by created timestamp descending and get one
        return $this->hasOne(Prediction::class, 'idphotos', 'idphotos')->orderBy('created', 'desc');
    }

}
