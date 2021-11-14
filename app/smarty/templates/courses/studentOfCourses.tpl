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
                        <table class="table table-bordered table-striped table-hover" id="datatable_student_of_courses"
                            width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Courses</th>
                                    <th>Score</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {* {if isset($list_data)}

                                    {foreach from=$list_data key=k item=i}
                                                <tr>
                                                    <td>{$i.id}</td>
                                                    <td>{$i.student_id}</td>
                                                    <td>{$i.courses_id}</td>
                                                    <td>{$i.score}</td>
                                                    <td></td>
                                                </tr>

                                    {/foreach}

                                {/if} *}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>

{include file="courses/updateStudent.tpl" }
{include file="share/footer.tpl"}

<script src="public/js/student_of_courses.js"></script>