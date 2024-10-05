const baseUrl = $('meta[name=app-url]').attr("content");
const url = baseUrl + '/user';
const form_id = 'form-data';
const form_submit_btn_id = 'form-data-submit-btn';
const modal_title = 'form-modal-title';
const modal_id = 'form-modal';
const datatable_id = 'dataTable';
$(document).ready(function () {
  $('#' + datatable_id).DataTable({
    processing: true,
    serverSide: true,
    // searching: true,
    responsive: true,
    // autoWidth: false,
    // bPaginate: true,
    // dom: 'Bfltip',
    pageLength: 10,
    ajax: url,
    "order": [[0, "desc"]],
    dom: "<'row'<'col'l><'col'B><'col'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    lengthMenu: [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, 'All']
    ],
    buttons: [
      {
        text: '<span class="fa fa-plus-circle" aria-hidden="true"></span> Create New',
        className: 'btn-warning',
        name: 'Create New',
        title: 'Create New',
        attr: {
          id: 'btn-add-review'
        },
        action: function (e, dt, node, config) {
          createData();
        }
      }
    ],
    columns: [
      { data: 'id' },
      { data: 'name' },
      { data: 'email' },
      { data: 'role_name' },
      { data: 'isDisable' },
      { data: 'action' },
    ],

  });
});
function reloadTable() {
  $('#' + datatable_id).DataTable().ajax.reload();
}

function showData(id, editData) {
  $("#formGroupPassword").css('display', 'none');
  $("#formGroupPasswordRe").css('display', 'none');
  $('#loader_page').addClass('show');
  document.getElementById(form_id).reset();
  $("#" + modal_title).html(editData == false ? "Show Data" : "Edit Data");
  if (editData == false) {
    $("#" + form_submit_btn_id).css('display', 'none');
    MakeAllReadOnly(form_id);
  } else {
    ClearAllReadOnly(form_id);
    $("#" + form_submit_btn_id).css('display', 'block');
  }
  $.ajax({
    url: url + "/" + id + "",
    type: "GET",
    success: function (response) {
      let data = response.data;
      //console.log(data);
      $("#id").val(data.id);
      $("#name").val(data.name);
      $("#email").val(data.email);
      $("#" + modal_id).modal('show');
      $('#loader_page').removeClass('show');
      if (editData == true) {
        getRoles(data.role_id);
      }else{
        $("#role_id").empty();
        $("#role_id").append("<option "+(data.role_id!=null?'':'selected')+" value=''>Choose role here</option>");
        if(data.role_id!=null){
          $("#role_id").append("<option selected value='"+data.role_id+"' >"+data.role_name+"</option>");
        }
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      $('#loader_page').removeClass('show');
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  }).always(function () {
    //$('#loader_page').removeClass('show');

  });
}
function getRoles(selectedRoleId) {
  $('#loader_page').addClass('show');
  $.ajax({
    url: url + "/getRoles",
    type: "GET",
    success: function (response) {
      let data = response.data;
      $("#role_id").empty();
      $("#role_id").append("<option "+(selectedRoleId!=null?'':'selected')+" value=''>Choose role here</option>");
      $.each(data,
        function (i, c) {
          //console.log(c,selectedRoleId,selectedRoleId==c.id);
          $("#role_id").append('<option value="' + c.id + '" '+(selectedRoleId==c.id?'selected':'')+'>' + c.name + '</option>');
        }
      );

    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  }).always(function () {
    $('#loader_page').removeClass('show');
  });
}

$("#" + form_id).on("submit", function (event) {
  event.preventDefault();

  if ($("#id").val() == null || $("#id").val() == "") {
    storeProject();
  } else {
    updateProject();
  }
})

function createData() {
  $("#formGroupPassword").css('display', 'block');
  $("#formGroupPasswordRe").css('display', 'block');
  getRoles(null);
  ClearAllReadOnly(form_id);
  $("#" + modal_title).html("Create Data New");
  document.getElementById(form_id).reset();
  $("#" + modal_id).modal('show');
}

function storeProject() {
  $('#loader_page').addClass('show');
  let data = $("#" + form_id).serializeArray().reduce(function (obj, item) {
    obj[item.name] = item.value;
    return obj;
  }, {});
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: url,
    type: "POST",
    data: data,
    success: function (response) {
      $("#" + modal_id).modal('hide');
      if(response.status) reloadTable();
      else{
        Swal.fire({
          icon: "error",
          title: "Eror Save Data",
          text: response.message,
        });
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  }).always(function () {
    $('#loader_page').removeClass('show');
  });
}


function updateProject() {
  $('#loader_page').addClass('show');
  let data = $("#" + form_id).serializeArray().reduce(function (obj, item) {
    obj[item.name] = item.value;
    return obj;
  }, {});
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: url + "/" + data.id + "",
    type: "PUT",
    data: data,
    success: function (response) {
      $("#" + modal_id).modal('hide');
      if(response.status) reloadTable();
      else{
        Swal.fire({
          icon: "error",
          title: "Eror Save Data",
          text: response.message,
        });
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  }).always(function () {
    $('#loader_page').removeClass('show');
  });
}


function destroyData(id) {
  Swal.fire({
    title: "Are you sure delete data id '" + id + "'?",
    text: "You will not be able to recover this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Yes, delete it!",
  }).then(result => {
    if (result.dismiss) {
      Swal.fire("Cancelled", "Your data is safe :)", "info");
    }
    if (result.value) {
      $('#loader_page').addClass('show');
      let data = {
        name: $("#name").val(),
      };
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url + "/" + id + "",
        type: "DELETE",
        data: data,
        success: function (response) {
          reloadTable();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
      }).always(function () {
        $('#loader_page').removeClass('show');
      });
    }
  });

}
function disableUser(id,actionName) {
  Swal.fire({
    title: "Are you sure "+actionName+" this user id '" + id + "'?",
    text: "Make sure the action!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Yes, "+actionName+" it!",
  }).then(result => {
    if (result.value) {
      $('#loader_page').addClass('show');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url + "/disable",
        type: "POST",
        data: {id:id},
        success: function (response) {
          reloadTable();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
      }).always(function () {
        $('#loader_page').removeClass('show');
      });
    }
  });

}