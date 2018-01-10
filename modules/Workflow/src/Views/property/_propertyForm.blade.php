<div class="container">
  @if(isset($property))
    
    <form id="frmProperty" name="frmProperty" method="post" action="{{ url('property/edit/'.$property->id) }}" enctype="multipart/form-data">
  @else
    
    <form id="frmProperty" name="frmProperty" method="post" action="{{ url('/property/create') }}" enctype="multipart/form-data">
  @endif
      <div class="form-group">
          <lable>description</lable>
          <input type="text" name="description" class="form-control">
      </div>
      <div class="form-group">
          <lable>image_url</lable>
          <input type="file" name="image_url" class="form-control">          
      </div>
      <div class="form-group">
          <button type="submit" class="btn btn-primary">
              @if(isset($property))
                Save changes
              @else
                Create Property
              @endif
          </button>
      </div>
 <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>
