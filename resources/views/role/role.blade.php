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
<!-- project form modal -->
<div class="modal" tabindex="-1" role="dialog" id="form-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Project Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="error-div"></div>
        <form>
            <input type="hidden" name="update_id" id="update_id">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" rows="3" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-outline-primary mt-3" id="save-project-btn">Save Project</button>
        </form>
      </div>
    </div>
  </div>
</div>
  
<!-- view project modal -->
<div class="modal " tabindex="-1" role="dialog" id="view-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Project Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <b>Name:</b>
        <p id="name-info"></p>
        <b>Description:</b>
        <p id="description-info"></p>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  
  $(function() {
      var baseUrl = $('meta[name=app-url]').attr("content");
      let url = baseUrl + '/role';
      // create a datatable
      $('#dataTable').DataTable({
          processing: true,
          ajax: url,
          "order": [[ 0, "desc" ]],
          columns: [
              { data: 'id'},
              { data: 'name'},
              { data: 'created_at'},
              { data: 'updated_at'},
              { data: 'action'},
          ],
            
      });
    });
    

  function reloadTable()
  {
      /*
          reload the data on the datatable
      */
      $('#projects_table').DataTable().ajax.reload();
  }

  /*
      check if form submitted is for creating or updating
  */
  $("#save-project-btn").click(function(event ){
      event.preventDefault();
      if($("#update_id").val() == null || $("#update_id").val() == "")
      {
          storeProject();
      } else {
          updateProject();
      }
  })

  /*
      show modal for creating a record and 
      empty the values of form and remove existing alerts
  */
  function createProject()
  {
      $("#alert-div").html("");
      $("#error-div").html("");
      $("#update_id").val("");
      $("#name").val("");
      $("#description").val("");
      $("#form-modal").modal('show'); 
  }

  /*
      submit the form and will be stored to the database
  */
  function storeProject()
  {   
      $("#save-project-btn").prop('disabled', true);
      let url = $('meta[name=app-url]').attr("content") + "/projects";
      let data = {
          name: $("#name").val(),
          description: $("#description").val(),
      };
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: url,
          type: "POST",
          data: data,
          success: function(response) {
              $("#save-project-btn").prop('disabled', false);
              let successHtml = '<div class="alert alert-success" role="alert"><b>Project Created Successfully</b></div>';
              $("#alert-div").html(successHtml);
              $("#name").val("");
              $("#description").val("");
              reloadTable();
              $("#form-modal").modal('hide');
          },
          error: function(response) {
              $("#save-project-btn").prop('disabled', false);
              if (typeof response.responseJSON.errors !== 'undefined') 
              {
  let errors = response.responseJSON.errors;
  let descriptionValidation = "";
  if (typeof errors.description !== 'undefined') 
                  {
                      descriptionValidation = '<li>' + errors.description[0] + '</li>';
                  }
                  let nameValidation = "";
  if (typeof errors.name !== 'undefined') 
                  {
                      nameValidation = '<li>' + errors.name[0] + '</li>';
                  }
    
  let errorHtml = '<div class="alert alert-danger" role="alert">' +
      '<b>Validation Error!</b>' +
      '<ul>' + nameValidation + descriptionValidation + '</ul>' +
  '</div>';
  $("#error-div").html(errorHtml);            
}
          }
      });
  }


  /*
      edit record function
      it will get the existing value and show the project form
  */
  function editProject(id)
  {
      let url = $('meta[name=app-url]').attr("content") + "/projects/" + id;
      $.ajax({
          url: url,
          type: "GET",
          success: function(response) {
              let project = response.project;
              $("#alert-div").html("");
              $("#error-div").html("");
$("#update_id").val(project.id);
$("#name").val(project.name);
$("#description").val(project.description);
$("#form-modal").modal('show'); 
          },
          error: function(response) {
              console.log(response.responseJSON)
          }
      });
  }

  /*
      sumbit the form and will update a record
  */
  function updateProject()
  {
      $("#save-project-btn").prop('disabled', true);
      let url = $('meta[name=app-url]').attr("content") + "/projects/" + $("#update_id").val();
      let data = {
          id: $("#update_id").val(),
          name: $("#name").val(),
          description: $("#description").val(),
      };
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: url,
          type: "PUT",
          data: data,
          success: function(response) {
              $("#save-project-btn").prop('disabled', false);
              let successHtml = '<div class="alert alert-success" role="alert"><b>Project Updated Successfully</b></div>';
              $("#alert-div").html(successHtml);
              $("#name").val("");
              $("#description").val("");
              reloadTable();
              $("#form-modal").modal('hide');
          },
          error: function(response) {
              $("#save-project-btn").prop('disabled', false);
              if (typeof response.responseJSON.errors !== 'undefined') 
              {
  let errors = response.responseJSON.errors;
  let descriptionValidation = "";
  if (typeof errors.description !== 'undefined') 
                  {
                      descriptionValidation = '<li>' + errors.description[0] + '</li>';
                  }
                  let nameValidation = "";
  if (typeof errors.name !== 'undefined') 
                  {
                      nameValidation = '<li>' + errors.name[0] + '</li>';
                  }
    
  let errorHtml = '<div class="alert alert-danger" role="alert">' +
      '<b>Validation Error!</b>' +
      '<ul>' + nameValidation + descriptionValidation + '</ul>' +
  '</div>';
  $("#error-div").html(errorHtml);            
}
          }
      });
  }

  /*
      get and display the record info on modal
  */
  function showProject(id)
  {
      $("#name-info").html("");
      $("#description-info").html("");
      let url = $('meta[name=app-url]').attr("content") + "/role/" + id +"";
      $.ajax({
          url: url,
          type: "GET",
          success: function(response) {
              let project = response.project;
              $("#name-info").html(project.name);
$("#description-info").html(project.description);
$("#view-modal").modal('show'); 

          },
          error: function(response) {
              console.log(response.responseJSON)
          }
      });
  }

  /*
      delete record function
  */
  function destroyProject(id)
  {
      let url = $('meta[name=app-url]').attr("content") + "/projects/" + id;
      let data = {
          name: $("#name").val(),
          description: $("#description").val(),
      };
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: url,
          type: "DELETE",
          data: data,
          success: function(response) {
              let successHtml = '<div class="alert alert-success" role="alert"><b>Project Deleted Successfully</b></div>';
              $("#alert-div").html(successHtml);
              reloadTable();
          },
          error: function(response) {
              console.log(response.responseJSON)
          }
      });
  }
</script>
@endsection