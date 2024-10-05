const baseUrl = $('meta[name=app-url]').attr("content");
const url = baseUrl + '/module';
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
    "order": [[4, "desc"]],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, 'All']
    ],
    columns: [
      { data: 'id' },
      { data: 'ModuleName' },
      { data: 'ModuleGroup' },
      { data: 'ModuleSubGroup' },
      { data: 'ModuleOrder' },
      { data: 'action' },
    ],

  });
});
function reloadTable() {
  $('#' + datatable_id).DataTable().ajax.reload();
}

function showData(id, editData) {
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
      $("#ModuleName").val(data.ModuleName);
      $("#ModuleGroup").val(data.ModuleGroup);
      $("#ModuleSubGroup").val(data.ModuleSubGroup);
      $("#ModuleOrder").val(data.ModuleOrder);
      $("#ModuleIcon").val(data.ModuleIcon);

      $("#ModuleController").val(data.ModuleController);
      $("#ModuleAction").val(data.ModuleAction);

      $("#" + modal_id).modal('show');
      $('#loader_page').removeClass('show');
    },
    error: function (xhr, ajaxOptions, thrownError) {
      $('#loader_page').removeClass('show');
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  }).always(function () {
    //$('#loader_page').removeClass('show');

  });
}

$("#" + form_id).on("submit", function (event) {
  event.preventDefault();

  updateProject();
})

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