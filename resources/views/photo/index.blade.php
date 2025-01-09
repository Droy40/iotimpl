@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($photos as $photo)
            <div class="col" >
                <div class="card h-100">
                    <img src={{$photo->location}} class="card-img-top" alt="Card Image">
                    <div class="card-body">
                        <h5 class="card-title">{{$photo->typePrediction->created}}</h5>
                        <h5 class="card-text">{{$photo->typePrediction->detail->first()->tagName . " " .number_format((float)$photo->typePrediction->detail->first()->probability * 100, 2, '.', '') . "%"}}</h5>
                    </div>
                    <div class="card-footer">
                        @foreach($photo->qualityPrediction->detail as $predict)
                            <h5 class="card-title">{{$predict->tagName}}</h5>
                            <p class="card-text">{{number_format((float)$predict->probability * 100, 2, '.', '') . "%"}}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
