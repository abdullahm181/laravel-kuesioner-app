@extends('layout.app')

@section('title')
Role Master
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800">Master Roles</h1>
</div>

<div class="row">
  <div class="col-xl-12 col-md-12">
     <!-- DataTales Example -->
     <div class="card shadow mb-4">
      <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
      </div>
      <div class="card-body">
          <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                      <tr>
                          <th>id</th>
                          <th>name</th>
                          <th>created_at</th>
                          <th>updated_at</th>
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
    <button type="submit" class="btn btn-outline-primary mt-3" id="form-data-submit-btn">
        Save data
    </button>
</form>
@endsection
@section('script')
{{-- <script type="text/javascript">
  
</script> --}}
<script src="{{asset('js/page/role.js')}}"></script>
@endsection