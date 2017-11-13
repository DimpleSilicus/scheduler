<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
@extends('layouts.app')

@section('content')
<h3>Hello Scheduler</h3>

<div class="container">
    <h2>Scheduler Details</h2>


    <button type="button" class="btn btn-green margin-B-20" data-toggle="modal" data-target="#addScheduler">Add scheduler</button>
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has($msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
        @endif
        @endforeach
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Time</th>
                <th>Interval</th>
                <th>Status</th>
                <th>Action</th>
            </tr>


        </thead>
        <tbody>
            @if (count($allSchedulerResult) > 0)
            @foreach ($allSchedulerResult as $request)
            <tr>
                <td>{{$request['name']}}</td>
                <td>{{$request['time_of_day']}}</td>
                <td>{{$request['interval']}}</td>
                <td>{{ isset($request['status']) ? $request['status'] == 1 ? 'Active' : 'Deleted' : 'N/A'}}</td>
                <td>

                    <div>
                        <!--<input type="hidden" name="schedulerId" id="schedulerId" value="{{$request['sid']}}"/>-->
                        <a href="#" id="editScheduler" schedulerId={{$request['sid']}} class="table-icon">                                                           
                            <span class="glyphicon glyphicon-edit"></span>                           
                        </a>
                        <a href="#" id="deleteScheduler" class="table-icon" onclick="deleteScheduler()" >

                            <!--<button type="submit" class="btn btn-default btn-sm deleteScheduler">-->
                            <span class="glyphicon glyphicon-trash"></span>  
                            <!--</button>-->
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach

            @endif

        </tbody>
    </table>
</div>


<div id="addScheduler" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Scheduler</h4>
            </div>
            <div class="modal-body">
                <form id="frmAddScheduler" name="frmAddScheduler" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="eventRowsData" id="eventRowsData" value="" />
                    <div class="col-sm-12">
                        <div class="row upload-Gedcom-file">
                            <div class="col-sm-12">
                                <h5>Scheduler Name *</h5>
                                <div class="input-group width-100Per">
                                    <input class="form-control" id="schedulerName" name="schedulerName" type="text" required>
                                        <span class="form-highlight"></span>
                                        <span class="form-bar"></span>
                                        <span class="text-danger" id="schedulerName-div"><strong id="form-errors-schedulerName"></strong>
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
                                    <span class="text-danger" id="schedulerType-div"><strong id="form-errors-schedulerType"></strong>
                                        <br/>
                                        <div id="Template" style="display: none;">

                                        </div>
                                </div>
                                <div class="input-group width-100Per">                                                
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h5>Scheduler From Date </h5>
                                            <input type="text" class="form-control" id="schedulerFromDate" name="schedulerFromDate" value=""/>

                                            <span class="form-highlight"></span>
                                            <span class="form-bar"></span>
                                            <span class="text-danger" id="schedulerDate-div">
                                                <strong id="form-errors-schedulerDate"></strong></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <h5>Scheduler To Date </h5>
                                            <input type="text" class="form-control" id="schedulerToDate" name="schedulerToDate" value=""/>

                                            <span class="form-highlight"></span>
                                            <span class="form-bar"></span>
                                            <span class="text-danger" id="schedulerDate-div">
                                                <strong id="form-errors-schedulerDate"></strong></span>
                                        </div>
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
                                    <span class="text-danger" id="schedulerInterval-div"><strong id="form-errors-schedulerInterval"></strong>
                                </div>
                                            
                                <div id="schedulerDate">
<!--                                    <h5>Scheduler Time *</h5>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="schedulerDateMultiple" name="schedulerDateMultiple[]" value="" required/>

                                            <span class="form-highlight"></span>
                                            <span class="form-bar"></span>
                                            <span class="text-danger" id="schedulerDate-div">
                                                <strong id="form-errors-schedulerDate"></strong></span>                                            
                                        </div>
                                        <div class="col-sm-1">
                                            plus button come here
                                            <a href="#" id="addDateTime-ico-plus" class="table-icon">
                                                <span class="glyphicon glyphicon-plus"></span>                           
                                            </a>
                                        </div>
                                                        <div class="col-sm-1">
                                            minus button come here
                                              <a href="#" id="deleteDateTime" class="table-icon ">                                                           
                                                  <span class="glyphicon glyphicon-minus"></span>                           
                                              </a>
                                           </div>
                                    </div>
                                    <div id="eventRows">

                                    </div>-->
                                </div>
                                <div id="DynamicData" style="display: none;">
                                    
                                </div>

                                        </div>
                                    </div><!--row end-->
                                </div>

                                <div class="clearfix"></div>
                                <div class="modal-footer margin-T-40">
                                    <input type="hidden" name="type" id="type" value="add"/>
                                    <button type="submit" class="btn btn-raised btn-green pull-right">Add</button>
                                    <button type="submit" class="btn btn-raised btn-default pull-right margin-R-20" data-dismiss="modal" >Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

<div id="editSchedulerPopup" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Scheduler</h4>
            </div>
        <div class="modal-body">
            <form id="frmEditScheduler" name="frmEditScheduler" method="post" enctype="multipart/form-data">
                <input type="hidden" name="schedulerId" id="schedulerId" value="" />
                <div class="col-sm-12">
                    <div class="row upload-Gedcom-file">
                        <div class="col-sm-12">
                            <h5>Scheduler Name *</h5>
                            <div class="input-group width-100Per">
                                <input class="form-control" id="schedulerNameU" name="schedulerNameU" type="text" required>
                                    <span class="form-highlight"></span>
                                    <span class="form-bar"></span>
                                    <span class="text-danger" id="schedulerNameU-div"><strong id="form-errors-schedulerNameU"></strong>
                            </div>
                            <h5>Scheduler Type *</h5>

                            <div class="input-group width-100Per">
                                <select class="form-control has-info" id="schedulerTypeU" name="schedulerTypeU" onchange="showTemplate(this.value)" placeholder="Placeholder" required>
                                    <option selected="selected" value="">Select Scheduler Type</option>                                        
                                    <option value="email">Email</option>
                                    <option value="batch">Batch</option>
                                </select>
                                <span class="form-highlight"></span>
                                <span class="form-bar"></span>
                                <span class="text-danger" id="schedulerTypeU-div"><strong id="form-errors-schedulerTypeU"></strong>
                                    <br/>
                                    <div id="Template">

                                    </div>
                            </div>
                            <div class="input-group width-100Per">                                                
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h5>Scheduler From Date </h5>
                                        <input type="text" class="form-control" id="schedulerFromDateU" name="schedulerFromDateU" value=""/>

                                        <span class="form-highlight"></span>
                                        <span class="form-bar"></span>
                                        <span class="text-danger" id="schedulerDateU-div">
                                            <strong id="form-errors-schedulerDateU"></strong></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <h5>Scheduler To Date </h5>
                                        <input type="text" class="form-control" id="schedulerToDateU" name="schedulerToDateU" value=""/>

                                        <span class="form-highlight"></span>
                                        <span class="form-bar"></span>
                                        <span class="text-danger" id="schedulerDateU-div">
                                            <strong id="form-errors-schedulerDateU"></strong></span>
                                    </div>
                                </div>
                            </div>
                            <h5>Scheduler Interval *</h5>

                            <div class="input-group width-100Per">
                                <select class="form-control has-info" id="schedulerIntervalU" name="schedulerIntervalU" onchange="GetSchedulerTime(this.value)" placeholder="Placeholder" required>
                                    <option selected="selected" value="">Select Interval</option>                                        
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>

                                </select>
                                <span class="form-highlight"></span>
                                <span class="form-bar"></span>
                                <span class="text-danger" id="schedulerIntervalU-div"><strong id="form-errors-schedulerIntervalU"></strong>
                            </div>
                            <div id="schedulerDateU">
                                <h5>Scheduler Time *</h5>
                                <div class="row editTime">
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="schedulerDateMultiple" name="schedulerDateMultiple" value=""/>

                                            <span class="form-highlight"></span>
                                            <span class="form-bar"></span>
                                            <span class="text-danger" id="schedulerDate-div">
                                                <strong id="form-errors-schedulerDate"></strong></span>                                            
                                        </div>
                                        <div class="col-sm-1">
                                            <!--plus button come here-->
                                            <a href="#" id="editTime-ico-plus" class="table-icon">
                                                <span class="glyphicon glyphicon-plus"></span>                           
                                            </a>
                                        </div>
    <!--                                                    <div class="col-sm-1">
                                            minus button come here
                                              <a href="#" id="deleteDateTime" class="table-icon ">                                                           
                                                  <span class="glyphicon glyphicon-minus"></span>                           
                                              </a>
                                           </div>-->
                                    </div>
                                    <div id="eventRows">

                                    </div>
<!--                                <div class="input-group">
                                    <input type="text" class="form-control" id="schedulerDateU" name="schedulerDateU" required/>
                                    <span class="form-highlight"></span>
                                    <span class="form-bar"></span>
                                    <span class="text-danger" id="schedulerDate-div"><strong id="form-errors-schedulerDate"></strong><label class="hasdrodown" for="personDob">Date</label><label class="input-group-addon modal-datepicker-ico" for="schedulerDate"><span class="glyphicon glyphicon-th"></span></label><span class="text-danger" id="personEvents-div"><strong id="form-errors-personEvents"></strong>
                                            </div>-->
                            </div>                                

                        </div>
                    </div><!--row end-->
                </div>

                <div class="clearfix"></div>
                <div class="modal-footer margin-T-40">

                    <button type="submit" class="btn btn-raised btn-green pull-right">Update</button>
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


