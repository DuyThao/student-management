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
                    checkResponse(result, 'Create Success', 'datatable_users', 'add_modal')
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
        ],
        
    });
}


