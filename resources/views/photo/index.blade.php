@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($photos as $photo)
            @php
                $prediction = $photo->predictions->first();
                $topDetail = $prediction && $prediction->details->first() ? $prediction->details->first() : null;
            @endphp
            <div class="col" >
                <div class="card h-100">
                    <img src={{ $photo->location }} class="card-img-top" alt="Card Image">
                    <div class="card-body">
                        <h5 class="card-title">{{ $prediction ? \Illuminate\Support\Carbon::parse($prediction->created)->format('d M Y H:i') : 'No prediction' }}</h5>
                        <h5 class="card-text">
                            @if($topDetail)
                                {{ $topDetail->tagName . " " . number_format((float)$topDetail->probability * 100, 2, '.', '') . "%" }}
                            @else
                                -
                            @endif
                        </h5>
                    </div>
                    <div class="card-footer">
                        @if($prediction && $prediction->details->count())
                            @foreach($prediction->details as $predict)
                                <h5 class="card-title">{{ $predict->tagName }}</h5>
                                <p class="card-text">{{ number_format((float)$predict->probability * 100, 2, '.', '') . "%" }}</p>
                            @endforeach
                        @else
                            <p class="text-muted">No detailed predictions</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
