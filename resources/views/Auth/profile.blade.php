@extends('layout.app')

@section('title')
Profile
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800">Profile</h1>
</div>

<div class="row">
  <div class="col-xl-12 col-md-12">
     <!-- DataTales Example -->
     <div class="card shadow mb-4">
      <div class="card-body">
        <form id="formProfile" method="POST" action="javascript:void(0);">
          <input style="display: none" type="number" name="id" id="id" value="{{$dataUser->id}}">
          <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" name="name" value="{{$dataUser->name}}">
          </div>
          <div class="form-group">
              <label for="email">email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{$dataUser->email}}">
          </div>
          <div class="form-group">
              <label for="role_id">role</label>
              <input style="display: none" type="number" name="role_id" id="role_id" value="{{$dataUser->role_id}}">
              <span class="form-control">
                {{$dataUser->role_name}}
               </span>
          </div>
          <button type="submit" class="btn btn-block btn-outline-primary mt-3" id="form-data-submit-btn">
              Save data
          </button>
      </form>
      </div>
  </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
  $(document).ready(function() {
      $("#formProfile").on("submit", function(event) {
          event.preventDefault();

          $('#loader_page').addClass('show');
          let data = $("#formProfile").serializeArray().reduce(function(obj, item) {
              obj[item.name] = item.value;
              return obj;
          }, {});
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              url: '{{ url('/storeProfile') }}',
              type: "POST",
              data: data,
              success: function(response) {
                  if (response.status) {
                      Swal.fire({
                          title: "Success save profile!",
                          text: "",
                          icon: "success"
                      });
                  } else {
                      Swal.fire({
                          icon: "error",
                          title: "Error Save Data",
                          text: response.message,
                      });
                  }
              },
              error: function(xhr, ajaxOptions, thrownError) {
                  alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
              }
          }).always(function() {
              $('#loader_page').removeClass('show');
          });
      })
  });
</script>

@endsection