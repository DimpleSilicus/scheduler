<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--@extends('layouts.app')-->

@section('content')
<h3>Hello NAICS</h3>

<div class="container">
    <h2>NAICS Details</h2>    
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has($msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
        @endif
        @endforeach
    </div>
        
    <div class="modal-body">
        <form id="frmSearchNaics" name="frmSearchNaics" method="post" enctype="multipart/form-data">    
            <!--action="{{ url('/naicsdata') }}"-->
            <!--{!! csrf_field() !!}-->
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h5>NAICS CODE *</h5>
                        <div class="input-group">
                            <input class="form-control" id="NaicsCode" maxlength="6" name="NaicsCode" type="text" required />
                                <span class="form-highlight"></span>
                                <span class="form-bar"></span>
                                <span class="text-danger" id="schedulerName-div"><strong id="form-errors-schedulerName"></strong>
                        </div>    
                        <br></br>
                        <pre style="display: none;"></pre>
<!--                        <div id="DynamicData" style="display: none;">

                        </div>-->

                    </div>
                </div><!--row end-->
            </div>

            <div class="clearfix"></div>
            <div class="modal-footer margin-T-40">                                    
                <button type="button" class="btn btn-raised btn-green pull-right" onclick="viewNaicsResponse();">Search</button>
                <button type="reset" class="btn btn-raised btn-default pull-right margin-R-20">Reset</button>
            </div>
        </form>
    </div>
    </div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/js/bootstrap-datepicker.js"></script>
@if(isset($jsFiles))
@foreach($jsFiles as $src)
<script src="{{$src}}{{$jsTimeStamp}}"></script>
@endforeach
@endif

@endsection