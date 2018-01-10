@extends($theme.'.layouts.master')

@section('title')
Properties
@endsection

@section('hero')
<div class="hero-text">
    <h1>Lodging fit for a captain</h1>
    <p>The Next Generation of vacation rentals.</p>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="flash-message">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has($msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
            @endforeach
        </div>
        @foreach ($properties as $property)
        <div class="col-md-4">
            <a href="/property/{{ $property->id }}" class="property">

                <img src="{{ url('/').'/'.$property->image_url }}" />
                <h2>{{ $property->description }}</h2>
            </a>
        </div>
        @endforeach
    </div>
    <a href="{{ url('/properties') }}">Back to properties</a>
</div>
@endsection
