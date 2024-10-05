const baseUrl = $('meta[name=app-url]').attr("content");
const url = baseUrl + '/role';
const form_id='form-data';
const form_submit_btn_id='form-data-submit-btn';
const modal_title='form-modal-title';
const modal_id='form-modal';
const datatable_id='dataTable';
$(document).ready(function () {
  $('#'+datatable_id).DataTable({
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
    dom: "<'row'<'col'l><'col'B><'col'f>>"+ "<'row'<'col-sm-12'tr>>"+ "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    lengthMenu: [
      [10, 25, 50,100, -1],
      [10, 25, 50,100, 'All']
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
        action: function(e, dt, node, config) {
          createData();
        }
      }
  ],
    columns: [
      { data: 'id' },
      { data: 'name' },
      { 
        data: 'created_at',
        render: function(data, type, row){
            if(type === "sort" || type === "type"){
                return data;
            }
            return moment(data).format("DD MMM YY HH:mm");
        }
      },
      { 
        data: 'updated_at',
        render: function(data, type, row){
            if(type === "sort" || type === "type"){
                return data;
            }
            return moment(data).format("DD MMM YY HH:mm");
        }
      },
      { data: 'action' },
    ],

  });
});

function reloadTable() {
  $('#'+datatable_id).DataTable().ajax.reload();
}

function showData(id,editData) {
  $('#loader_page').addClass('show');
  document.getElementById(form_id).reset();
  $("#"+modal_title).html(editData==false?"Show Data":"Edit Data");
  if(editData==false){
    $("#"+form_submit_btn_id).css('display','none');
    MakeAllReadOnly(form_id);
  } else {
    ClearAllReadOnly(form_id);
    $("#"+form_submit_btn_id).css('display','block');
  }
  $.ajax({
    url: url + "/" + id + "",
    type: "GET",
    success: function (response) {
      let data = response.data;
      $("#name").val(data.name);
      $("#id").val(data.id);
      $("#"+modal_id).modal('show');     
    },
    error:function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  }).always(function() {
    $('#loader_page').removeClass('show');
  });
}

$("#"+form_id).on("submit", function (event) {
  event.preventDefault();
  
  if ($("#id").val() == null || $("#id").val() == "") {
    storeProject();
  } else {
    updateProject();
  }
})

function createData() {
  ClearAllReadOnly(form_id);
  $("#"+modal_title).html("Create Data New");
  document.getElementById(form_id).reset();
  $("#"+modal_id).modal('show'); 
}

function storeProject() {
  $('#loader_page').addClass('show');
  let data = $("#"+form_id).serializeArray().reduce(function(obj, item) {
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
      reloadTable();
      $("#"+modal_id).modal('hide'); 
    },
    error:function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  }).always(function() {
    $('#loader_page').removeClass('show');
  });
}


function updateProject() {
  $('#loader_page').addClass('show');
  let data = $("#"+form_id).serializeArray().reduce(function(obj, item) {
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
      reloadTable();
      $("#"+modal_id).modal('hide');
    },
    error:function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  }).always(function() {
    $('#loader_page').removeClass('show');
  });
}


/*
  delete record function
*/
function destroyData(id) {
  Swal.fire({
      title: "Are you sure delete data id '"+id+"'?",
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
          error:function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          }
        }).always(function() {
          $('#loader_page').removeClass('show');
        });
      }
  });
  
}