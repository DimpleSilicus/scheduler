@if (count($notifications) > 0)
@foreach ($notifications as $notification)
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button " class="close dismissNotification" did={{$notification["id"]}} data-dismiss="alert">
        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
    </button>
    <strong>{{$notification["message"]}}</strong>
</div>
@endforeach
@else
<div class="alert alert-danger center alert-dismissible" role="alert">
    <strong>No Notification Found.</strong>
</div>

@endif