@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Photo Details</h1>
    <div class="card">
        <img src="{{ $photo->location }}" class="card-img-top" alt="Photo">
        <div class="card-body">
            <h5 class="card-title">Photo ID: {{ $photo->idphotos }}</h5>
            <p class="card-text">Description: {{ $photo->description ?? '-' }}</p>
            <p class="card-text">Uploaded At: {{ $photo->created_at ? $photo->created_at->format('d M Y H:i') : '-' }}</p>
        </div>
        <div class="card-footer">
            @php
                $prediction = $photo->predictions->first();
            @endphp

            @if($prediction && $prediction->details->count())
                @foreach($prediction->details as $predict)
                    <h5 class="card-title">{{ $predict->tagName }}</h5>
                    <p class="card-text">{{ number_format((float)$predict->probability * 100, 2, '.', '') . "%" }}</p>
                @endforeach
            @else
                <p class="text-muted">No detailed predictions available</p>
            @endif

            <a href="{{ route('photo.index') }}" class="btn btn-primary">Back to Photos</a>
        </div>
    </div>
</div>
@endsection
