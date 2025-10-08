@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Photo Details</h1>
    <div class="card">
        <img src="{{ $photo->location }}" class="card-img-top" alt="Photo">
        <div class="card-body">
            <h5 class="card-title">Photo ID: {{ $photo->id }}</h5>
            <p class="card-text">Description: {{ $photo->description }}</p>
            <p class="card-text">Uploaded At: {{ $photo->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="card-footer">
            @foreach($photo->qualityPrediction->detail as $predict)
                <h5 class="card-title">{{$predict->tagName}}</h5>
                <p class="card-text">{{number_format((float)$predict->probability * 100, 2, '.', '') . "%"}}</p>
            @endforeach
            <a href="{{ route('photo.index') }}" class="btn btn-primary">Back to Photos</a>
        </div>
    </div>
</div>
@endsection
