
let top_student = false;

(function ($) {
    var student = function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#add_form').on('submit', function (e) {
            e.preventDefault();
            var data = {
                'name': $('#name').val(),
                'time': $('#time').val(),
            }
            token = $('#csrf_token').val()
            if ($('#name').val() != "" && $('#time').val() != "") {
                $.post("student-add", { data: data, token: token }, function (result) {
                    checkResponse(result, 'Create Success', 'datatable_student', 'add_modal')
                }).catch(function (error) {
                    Swal(error.statusText, '', 'error');
                })
            }
            else {
                return;
            }

        });

        $('#update_form').on('submit', function (e) {
            e.preventDefault();
            name=$('#update_name').val()
            time=$('#update_time').val()
            student_id= $('#update_form').attr('data-id')
            courses_id=$('#update_courses').val()
            score=$('#update_score').val()

            var data = {
                'name': name,
                'time': time,
                'id': student_id
            }
            var student_score = {
                student_id: student_id,
                courses_id : courses_id,
                score :score
            }
            token = $('#csrf_token').val()

            if (name != '' && time != '' && (courses_id != '' && score != '') || (courses_id == '' && score == '')) {
                    $.post("student-update", { data: data, token: token , student_score:student_score}, function (result) {
                        checkResponse(result, 'Update Success', 'datatable_student', 'edit_modal')
                    }).catch(function (error) {
                        Swal(error.statusText, '', 'error');
                    })
            }
            else {
                return;
            }

        });

        reload_table();

        $('#add_modal').on('hidden.bs.modal', function (e) {
            $('#name').val('')
            $('#time').val('')
            $('#add_form').attr('class', 'needs-validation');
        })
        $('#edit_modal').on('hidden.bs.modal', function (e) {
            $('#update_score').val('')
            $('#update_form').attr('class', 'needs-validation');
        })
        $('#update_courses').on('change', function (e) {
            data= {
                student_id: $('#update_form').attr('data-id'),
                courses_id:$("#update_courses").val()
            }
            $.post("student-get-score" , { data: data }, function (result) {
                result = JSON.parse(result);
                $("#update_score").val(result[0].score);
            }).catch(function (error) {
                $("#update_score").val("");
               

            })
        })


    };
    new student();
})($);


function getItem(id, name, time) {

    $('#update_form').attr('data-id', id);
    $('#update_name').val(name)
    time = time.replace(" ", "T");
    $('#update_time').val(time)
    $('#update_courses').val("")
    $('#update_score').val("")

}


function topStudent() {
    top_student = true
    var table = $('#datatable_student').DataTable();
    table.clear();
    table.destroy();
    $("#btn_top").attr("disabled", true);
    $("#btn_back").show();

    reload_table();

}
function back() {
    top_student = false
    var table = $('#datatable_student').DataTable();
    table.clear();
    table.destroy();
    reload_table();
    $("#btn_top").attr("disabled", false);
    $("#btn_back").hide();
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
                    checkResponse(result, 'Delete Success', 'datatable_student', 'modal')

                }).catch(function (error) {
                    console.log(error);
                    Swal('Delete Fail', '', 'error');

                })
        }
    })

}
function reload_table() {

    table = $('#datatable_student').dataTable({
        'scrollX': true,
        'pagingType': 'numbers',
        'processing': true,
        'serverSide': true,
        "ajax": {
            "url": "get-data-table-student",
            "type": "POST",
            "data": { top_student: top_student }
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
                    return `<div id="update_${data[0]}" class="btn btn-primary" data-toggle="modal" data-target="#edit_modal" 
                    data-whatever="@getbootstrap" onclick="getItem( '${data[0]}', '${data[1]}', '${data[2]}')" ><i class="far fa-edit" aria-hidden="true"></i></div> 
                    <button id="delete_${data[0]}" type="button" class="btn btn-danger" onclick="deleteStudent(${data[0]})" ><i class="fa fa-trash"></i></button>
                    <button id="courses_${data[0]}" type="button" class="btn btn-info" onclick="StudentOfCourses(${data[0]})" data-toggle="modal" data-target="#courses_modal" 
                    data-whatever="@getbootstrap"><i class="fa fa-list"></i></button>
                    `;
                }
            }
        ],
    });
}

function StudentOfCourses(student_id){
    var table_courses = $('#datatable_student_score').DataTable();
    table_courses.clear();
    table_courses.destroy();
    table_courses = $('#datatable_student_score').dataTable({
        'scrollX': false,
        'pagingType': 'numbers',
        'processing': true,
        'serverSide': true,
        'searching': false,
        "ajax": {
            "url": "get-data-table-student-score",
            "type": "POST",
            "data": { student_id: student_id }
        },
       
    });
    

}
