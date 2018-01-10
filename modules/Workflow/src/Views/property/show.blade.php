@extends($theme.'.layouts.master')

@section('title')
    Home
@endsection

@section('body_class')
  "property-page"
@endsection

@section('content')
<div class="property-detail">
    <div class="overview">
        <img src="{{ $property->image_url }}" />
    </div>
    <div class="container">
        @include($theme.'.layouts._messages')
        <h1 >{{ $property->description }}</h1>
        <span>
            <a href="{{ url('property/'.$property->id.'/edit') }}">Edit</a>
        </span>
        <hr>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Make a Reservation</h3>
            </div>
            <div class="panel-body">
                <form id="frmProperty" name="frmReservationCreate" method="post" action="{{ url('property/'.$property->id.'/reservation/create') }}" enctype="multipart/form-data">
                
                    <div class="form-group">
                        <lable>Message</lable>
                        <input type="text" name="message" class="form-control" placeholder="Hello! I am hoping to stay in your intergalactic suite...">                        
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="property_id" value="{{$property->id}}"/>                        
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Reserve Now</button>
                    </div>                
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
