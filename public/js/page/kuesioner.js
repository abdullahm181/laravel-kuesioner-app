const baseUrl = $('meta[name=app-url]').attr("content");
const url = baseUrl + '/kuesioner';
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
          window.location=url+'/manageKuesioner'
        }
      }
    ],
    columns: [
      { data: 'id' },
      { data: 'name' },
      { data: 'status' },
      { 
        data: 'startDate',
        render: function (data, type, row) {
          if (type === "sort" || type === "type") {
            return data;
          }
          return moment(data).format("DD MMM YY");
        }
       },
      { 
        data: 'endDate',
        render: function (data, type, row) {
          if (type === "sort" || type === "type") {
            return data;
          }
          return moment(data).format("DD MMM YY");
        }
       },
      { data: 'isAllowAnonymous' },
      { 
        data: 'updated_at',
        render: function (data, type, row) {
          if (type === "sort" || type === "type") {
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
  $('#' + datatable_id).DataTable().ajax.reload();
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