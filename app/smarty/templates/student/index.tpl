{include file="share/header.tpl" }

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"><a href="index.php"> Student</a></li>
            </ol>
            <div class="row">
            </div>
            <div class="form-row">
                <div class="col-md-4">
                    <input class="form-control py-4" name="search" type="text" id="search" />
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary" id="btn_search" onclick="searchStudent()"> Search
                    </button>

                </div>
            </div>
            <br>
            <div class="form-row">
                <div class="col-md-2">
                    <select class="form-control py-2" name="sort" id="sort">
                        <option value="id"> Select ...</option>
                        <option value="name"> Name</option>
                        <option value="courses"> Courses</option>
                        <option value="score"> Score</option>
                        <option value="time"> Time</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control py-2" name="sort_type" id="sort_type">
                        <option value="ASC"> Ascending</option>
                        <option value="DESC"> Descending</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary" id="btn_sort" onclick="searchStudent()"> Sort
                    </button>
                </div>

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
                </div>
                <div class="card-body">
                    <div class="table-responsive ">
                        <table class="table table-bordered table-striped table-hover" id="datatable_student"
                            width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th> Courses </th>
                                    <th> Score</th>
                                    <th> Time</th>
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