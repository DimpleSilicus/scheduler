@extends($theme.'.layouts.master')

@section('title')
    Edit Vacation Property
@endsection

@section('nav_bg')
  <!-- Nav Bar -->
  <nav class="navbar navbar-space">
  </nav>
@endsection

@section('content')
<div class="container">
  <h1>Editing Vacation Property</h1>
  @include('Workflow::property._propertyForm')

  <a href="{{ url('/properties') }}">Back to properties</a></li>
</div>
@endsection
