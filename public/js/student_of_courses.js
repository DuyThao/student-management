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
        $(document).ready(function() {
             
            table = $('#datatable_student_of_courses').dataTable({
                'scrollX': true,
                'pagingType': 'numbers',
                'processing': true,
                'serverSide': true,
                "ajax": {
                    "url": "get-data-table-student-of-courses",
                    "type": "POST",
                },
                "columns": [ 
                    { "data": "0" },
                    { "data": "1" },
                    { "data": "2" },
                    { "data": "3" },
                    { "data": "4" },
                    { "data": null }
                ],
                columnDefs: [
                    {
                        targets: 5,
                        orderable: false,
                        render: function (data) {
                            return `
                            <div id="update_${data[0]}" class="btn btn-primary" data-toggle="modal" data-target="#edit_modal" 
                            data-whatever="@getbootstrap" onclick="getItem( '${data[0]}', '${data[1]}', '${data[2]}', '${data[3]}','${data[4]}')" ><i class="far fa-edit" aria-hidden="true"></i></div> `;
                        }
                    }],
                // "columnDefs": [
                //     {"render": createManageBtn, "data": null, "targets": 5}
                // ],
            });

            console.log("@@@@@",table);
        });

        $("#datatable_student_of_courses").on("click", "button.edit", function() {
            var data = table.row($(this).parents("tr")).data();
            alert(data[0] + " I'm editing");
          });
    };
   
    new student_of_courses();
})($);

function createManageBtn() {
    return '<button id="manageBtn" type="button" class="btn btn-success btn-xs edit">Manage</button>';
}

// function myFunc() {
//     console.log("Button was clicked!!!");
// }

function getItem(id, name, courses, score, time) {

        $('#update_form').attr('data-id', id);
        $('#update_name').val(name)
        $('#update_courses').val(courses)
        $('#update_score').val(score)
        time = time.replace(" ", "T");
        $('#update_time').val(time)

}
function searchXSS() {
    text = $("#search_test").val();
    $.get("search-item/" + text, function (result) {
        $("#search_result").html("Search for: " + text)

    });

}

function topStudent() {
    top_student = true
    searchStudent();
}

function searchStudent() {
    search = $("#search").val();
    column = $("#sort").val();
    type = $("#sort_type").val();


    var table = $('#datatable_student').DataTable();
    table.clear();
    table.destroy();
    $('#datatable_student').dataTable({
        "ordering": false,
        "processing": true,
        "serverSide": true,
        "paging": true,
        "searching": false,
        "pageLength": 10,
        "pagingType": "full_numbers",
        "responsive": true,
        "ajax": {
            "url": "search-student",
            "type": "POST",
            "data": { search: search, column: column, type: type, top: top_student },
        },
        "fnRowCallback": function (nRow, aData) {
            $(nRow).attr("rowid", aData[0]);
            return nRow;
        },
    });
    top_student = false

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
function reload_table() {
    var table = $('#datatable_student').DataTable();
    table.clear();
    table.destroy();
    $('#datatable_student').dataTable({
        "ordering": false,
        "processing": true,
        "serverSide": true,
        "paging": true,
        "searching": false,
        "pageLength": 10,
        "pagingType": "full_numbers",
        "responsive": true,
        "ajax": {
            "url": "search-student",
            "type": "POST",
            "data": { search: search, column: column, type: type, top: top_student },
        },
        "fnRowCallback": function (nRow, aData) {
            $(nRow).attr("rowid", aData[0]);
            return nRow;
        },
    });
}

