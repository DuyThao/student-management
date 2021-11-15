let column = "id";
let type = "ASC";
let search = "";
let top_student = false;

(function ($) {
    var student_of_courses = function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        var url = window.location.href
        var id = url.substring(url.lastIndexOf('?') + 1);
       
        $(document).ready(function () {

            table = $('#datatable_student_of_courses').dataTable({
                'scrollX': true,
                'pagingType': 'numbers',
                'processing': true,
                'serverSide': true,
                "searching": false,
                "ajax": {
                    "url": "get-data-table-student-of-courses",
                    "type": "POST",
                    "data":{id:id}
                },
                "columns": [
                    { "data": "0" },
                    { "data": "1" },
                    { "data": "2" },
                    { "data": "3" },
                    { "data": null }
                ],
                columnDefs: [
                    {
                        targets: 4,
                        orderable: false,
                        render: function (data) {
                            return `
                            <div id="update_${data[0]}" class="btn btn-primary" data-toggle="modal" data-target="#edit_modal" 
                            data-whatever="@getbootstrap" onclick="getItem( '${data[0]}', '${data[1]}', '${data[2]}', '${data[3]}')" ><i class="far fa-edit" aria-hidden="true"></i></div> 
                            <button id="delete_${data[0]}" type="button" class="btn btn-danger" onclick="deleteStudent('${data[0]}')" ><i class="fa fa-trash"></i></button> `;
                        }
                    }],
            });
        });
        $('#add_form').on('submit', function (e) {
            e.preventDefault();
            var data = {
                'student_id': $('#name').val(),
                'score': $('#score').val(),
            }
            token = $('#csrf_token').val()
            if ($('#name').val() != "" && $('#score').val() != "" ) {
                $.post("student-of-courses-add", { data: data, token: token }, function (result) {
                    swal({
                        title: 'Create Success',
                        type: 'success',
                        timer: 1000,
                        buttons: true,
                    })
                    $('#datatable_student_of_courses').DataTable().ajax.reload();
                    $("#add_modal").modal('hide');

                }).catch(function (error) {
                    console.log(error);
                    Swal('Create fail', '', 'error');
                })
            }
            else {
                return;
            }

        });

        $('#update_form').on('submit', function (e) {
            e.preventDefault();

            var data = {
                'score': $('#update_score').val(),
                'id': $('#update_form').attr('data-id'),
            }
            token = $('#csrf_token').val()

            if ($('#update_score').val() != "" ) {
                $.post("student-update-score", { data: data, token: token }, function (result) {
                    console.log(result);
                    swal({
                        title: 'Update Success',
                        type: 'success',
                        timer: 1000,
                        buttons: true,
                    })
                    $('#datatable_student_of_courses').DataTable().ajax.reload();

                    $("#edit_modal").modal('hide');


                }).catch(function (error) {
                    console.log(error);
                    Swal('Update fail', '', 'error');
                })
            }
            else {
                return;
            }

        });
        $('#add_modal').on('hidden.bs.modal', function (e) {
            $('#name').val('')
            $('#score').val('')
            $('#add_form').attr('class', 'needs-validation');
        })

    };

    new student_of_courses();
})($);

function createManageBtn() {
    return '<button id="manageBtn" type="button" class="btn btn-success btn-xs edit">Manage</button>';
}


function getItem(id, name, courses, score) {

    $('#update_form').attr('data-id', id);
    $('#update_name').val(name)
    $('#update_score').val(score)

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

            axios.post('student-of-courses-delete/' + id, token)
                .then(res => {
                    swal({
                        title: 'Delete Success',
                        type: 'success',
                        timer: 1000,
                        buttons: true,
                    })
                    $('#datatable_student_of_courses').DataTable().ajax.reload();

                }).catch(function (error) {
                    console.log(error);
                    Swal('Update fail', '', 'error');
                })
        }
    })

}

