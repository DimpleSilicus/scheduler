<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
@extends('layouts.app')

@section('content')
<h3>Hello Scheduler</h3>

<div class="container">
  <h2>Scheduler Details</h2>
  
  <!--<a href="#" onclick="addScheduler();"><span class="glyphicon glyphicon-plus" ></span>add scheduler</a>-->
  <button type="button" class="btn btn-green margin-B-20" data-toggle="modal" data-target="#addScheduler">Add scheduler</button>
  <table class="table">
    <thead>
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
      </tr>
      <tr>
        <td>Mary</td>
        <td>Moe</td>
        <td>mary@example.com</td>
      </tr>
      <tr>
        <td>July</td>
        <td>Dooley</td>
        <td>july@example.com</td>
      </tr>
    </tbody>
  </table>
  
    
</div>


<div id="addScheduler" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload Picture</h4>
                </div>
                <div class="modal-body">
                    <form id="frmAddScheduler" name="frmAddScheduler" method="post" enctype="multipart/form-data">
                        <div class="col-sm-12">
                            <div class="row upload-Gedcom-file">
                            
                                <div class="col-sm-12">
                                <h5>Scheduler Name *</h5>
                                <div class="input-group width-100Per">
                                    <input class="form-control" id="schedulerName" name="schedulerName" type="text" required>
                                    <span class="form-highlight"></span>
                                    <span class="form-bar"></span>
                                  	<span class="text-danger" id="filename-div"><strong id="form-errors-filename"></strong>
                                </div>
                                <h5>Scheduler Type *</h5>
                                
                                <div class="input-group width-100Per">
                                <select class="form-control has-info" id="schedulerType" name="schedulerType" onchange="showTemplate(this.value)" placeholder="Placeholder" required>
                                        <option selected="selected" value="">Select Scheduler Type</option>                                        
                                        <option value="email">Email</option>
                                        <option value="batch">Batch</option>
                                    </select>
                                    <span class="form-highlight"></span>
                                    <span class="form-bar"></span>
                                  	<span class="text-danger" id="filename-div"><strong id="form-errors-filename"></strong>
                                        <br/>
                                        <div id='Template'>
                                            
                                        </div>
                                </div>
                                <h5>Scheduler Interval *</h5>
                                
                                <div class="input-group width-100Per">
                                    <select class="form-control has-info" id="schedulerInterval" name="schedulerInterval" onchange="GetSchedulerTime(this.value)" placeholder="Placeholder" required>
                                        <option selected="selected" value="">Select Interval</option>                                        
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                        
                                    </select>
                                    <span class="form-highlight"></span>
                                    <span class="form-bar"></span>
                                  	<span class="text-danger" id="filename-div"><strong id="form-errors-filename"></strong>
                                </div>
                                <div id="schedulerDate">
                                    <h5>Scheduler Time *</h5>
                                </div>                                
                                   
                                </div>
                            </div><!--row end-->
                        </div>
                   
                <div class="clearfix"></div>
                <div class="modal-footer margin-T-40">
                    <button type="submit" class="btn btn-raised btn-green pull-right">Add</button>
                    <button type="submit" class="btn btn-raised btn-default pull-right margin-R-20" data-dismiss="modal" >Cancel</button>
                </div>
                </form>
                </div>
            </div>
        </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/js/bootstrap-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
    @if(isset($jsFiles))
    @foreach($jsFiles as $src)
    <script src="{{$src}}{{$jsTimeStamp}}"></script>
    @endforeach
    @endif
    
    @if(isset($cssFiles))
        @foreach($cssFiles as $src)
        <link href="{{$src}}{{$cssTimeStamp}}" rel="stylesheet" type="text/css" />
        @endforeach
        @endif
@endsection