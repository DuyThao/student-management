(function ($) {
    var courses = function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        reload_table();
        //add courses
        $('#add_form').on('submit', function (e) {
            e.preventDefault();
            var data = {
                'name': $('#name').val(),
            }
            token = $('#csrf_token').val()
            if ($('#name').val() != "") {
                $.post("courses-add", { data: data, token: token }, function (result) {
                    checkResponse(result, 'Create Success', 'datatable_courses', 'add_modal')

                }).catch(function (error) {
                    console.log(error);
                    Swal('Create fail', '', 'error');
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
    new courses();
})($);
function reload_table() {

    table = $('#datatable_courses').dataTable({
        'scrollX': true,
        'pagingType': 'numbers',
        'processing': true,
        'serverSide': true,
        "ajax": {
            "url": "get-data-table-courses",
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

function clicka(courses_id) {
    $.post("courses-id", { courses_id: courses_id }, function (result) {
    });
}
function createManageBtn() {
    return '<button id="manageBtn" type="button" class="btn btn-success btn-xs edit">Manage</button>';
}

function getItem(id, name) {

    $('#update_form').attr('data-id', id);
    $('#update_name').val(name)
    $('#update_courses').val(courses)
    $('#update_score').val(score)
    time = time.replace(" ", "T");
    $('#update_time').val(time)

}
