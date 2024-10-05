@extends('layout.app')

@section('title')
User Master
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800">Master User</h1>
</div>

<div class="row">
  <div class="col-xl-12 col-md-12">
     <!-- DataTales Example -->
     <div class="card shadow mb-4">
      <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
      </div>
      <div class="card-body">
          <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                      <tr>
                          <th>id</th>
                          <th>name</th>
                          <th>email</th>
                          <th>role_name</th>
                          <th>disable</th>
                          <th>action</th>
                      </tr>
                  </thead>
                  <tbody id="dataTable_body">
                     
                  </tbody>
              </table>
          </div>
      </div>
  </div>
  </div>
</div>
@endsection

@section('form-modal')
<form id="form-data" method="POST" action="javascript:void(0);">
    <input style="display: none" name="id" id="id">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="form-group">
        <label for="email">email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="form-group">
        <label for="role_id">role</label>
        <select class="form-control" id="role_id" name="role_id">
            <option selected value=''>Choose role here</option>
         </select>
    </div>
    <div class="form-group" id='formGroupPassword'>
        <label for="password">Password</label>
        <input type="text" class="form-control form-control-user" id="password" name="password" placeholder="Password">
    </div>
    <div class="form-group" id='formGroupPasswordRe'>
        <label for="password_confirmation">Repeat Password</label>
        <input type="text" class="form-control form-control-user" id="password_confirmation" name="password_confirmation" placeholder="Repeat Password">
    </div>
    <button type="submit" class="btn btn-outline-primary mt-3" id="form-data-submit-btn">
        Save data
    </button>
</form>
@endsection
@section('script')
{{-- <script type="text/javascript">
  
</script> --}}
<script src="{{asset('js/page/user.js')}}"></script>
@endsection