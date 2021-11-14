{include file="share/header.tpl" }

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"><a href="courses-list"> Courses</a></li>
                <li class="breadcrumb-item active"><a href="student-of-courses"> Student</a></li>
            </ol>
            <div class="row">
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Table Courses
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                        data-target="#add_modal" data-whatever="@getbootstrap" id="btn_add_popup"><i class="fa fa-plus"
                            aria-hidden="true"></i>Add Courses </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive ">
                        <table class="table table-bordered table-striped table-hover" id="datatable_courses"
                            width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                           
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>

{include file="courses/addCourses.tpl" }
{* {include file="courses/updateCourses.tpl" } *}

{include file="share/footer.tpl"}
<script src="public/js/courses.js"></script>