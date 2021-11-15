{include file="share/header.tpl" }

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
           
            <div class="row">
            </div>
            <br>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Table Student
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                        data-target="#add_modal" data-whatever="@getbootstrap" id="btn_add_popup"><i class="fa fa-plus"
                            aria-hidden="true"></i>Add Student </button>

                    <button type="submit" class="btn btn-success float-right" style="margin-right: 10px" id="btn_top"
                        onclick="topStudent()"> Top 3
                        Student
                    </button>
                    <button type="submit" class="btn btn-secondary float-right" style="margin-right: 10px ; display:none" id="btn_back"
                        onclick="back()" > Back
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive ">
                        <table class="table table-bordered table-striped table-hover" id="datatable_student"
                            width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th> Time</th>
                                    <th> Score</th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>

{include file="student/addStudent.tpl" }
{include file="student/updateStudent.tpl" }

{include file="share/footer.tpl"}
<script src="public/js/student.js"></script>