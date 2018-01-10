@extends($theme.'.layouts.app')

@section('content')
<div class="container">
<!--    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>-->
</div>
<div>
    <!--<a href="/schedulerHome">Scheduler</a>-->
    <ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Home</a></li>
  <li role="presentation"><a href="/schedulerHome">Scheduler</a></li>
  <li role="presentation"><a href="/workflow">Workflow</a></li>
</ul>
</div>
@endsection
