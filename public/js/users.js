(function ($) {
    var users = function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        reload_table();
        //add user
        $('#add_form').on('submit', function (e) {
            e.preventDefault();
            var data = {
                'username': $('#username').val(),
                'password': $('#password').val(),
            }
            token = $('#csrf_token').val()
            if ($('#username').val() != "" && $('#password').val() != "") {
                $.post("users-add", { data: data, token: token }, function (result) {

                    swal({
                        title: 'Create Success',
                        type: 'success',
                        timer: 1000,
                        buttons: true,
                    })
                    $('#datatable_users').DataTable().ajax.reload();
                    $("#add_modal").modal('hide');

                }).catch(function (error) {
                    console.log(error);
                    Swal(error.statusText, '', 'error');
                })
            }
            else {
                return;
            }

        });
        $('#add_modal').on('hidden.bs.modal', function (e) {
            
            $('#name').val('')
            $('#add_form').attr('class', 'needs-validation');
        })


    };
    new users();
})($);
function reload_table() {

    table = $('#datatable_users').dataTable({
        'scrollX': true,
        'pagingType': 'numbers',
        'processing': true,
        'serverSide': true,
        "ajax": {
            "url": "get-data-table-users",
            "type": "POST",
        },
        "columns": [
            { "data": "0" },
            { "data": "1" },
            { "data": null }
        ],
        columnDefs: [
            {
                targets: 2,
                orderable: false,
                render: function (data) {
                    return `
                    <a href="student-of-courses?${data[0]}" onClick="clicka( '${data[0]}')" class="btn btn-primary"><i class="fas fa-list"></i></a>`;
                }
            }
        ],
    });
}



function getItem(id, name) {

    $('#update_form').attr('data-id', id);
    $('#update_name').val(name)
    $('#update_courses').val(courses)
    $('#update_score').val(score)
    time = time.replace(" ", "T");
    $('#update_time').val(time)

}
function deleteStudent(id) {
    token = $('#csrf_token').val()

    swal({
        title: 'Are you sure?',
        text: "What do you want to delete it?",
        type: 'warning',
        showCancelButton: true,

        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Confirm'
    }).then((result) => {

        if (result.value) {

            axios.post('student-delete/' + id, token)
                .then(res => {
                    swal({
                        title: 'Delete Success',
                        type: 'success',
                        timer: 1000,
                        buttons: true,
                    })
                    reload_table()
                }).catch(function (error) {
                    console.log(error);
                    Swal('Update fail', '', 'error');

                })
        }
    })

}

