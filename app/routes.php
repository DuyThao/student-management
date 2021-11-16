<?php


    //student page
    $router->get('/', 'StudentController@index');
    $router->get('/student-list', 'StudentController@index');

    $router->get('/student-add', 'StudentController@add');

    $router->post('/student-add', 'StudentController@createStudent');

    $router->post('/get-data-table-student', 'StudentController@getDataTable');

    $router->post('/student-delete/{id}', 'StudentController@deleteStudent');

    $router->post('/student-get-item/{id}', 'StudentController@getItemStudent');

    $router->post('/student-update', 'StudentController@updateStudent');

    $router->post('/search-student', 'StudentController@searchStudent');

    $router->post('/student-get-score', 'StudentController@getScore');

   

    //courses page

    $router->get('/courses-list', 'CoursesController@index');
    $router->post('/get-data-table-courses', 'CoursesController@getDatatable');

    $router->post('/courses-add', 'CoursesController@createCourses');
    $router->post('/courses-id', 'CoursesController@saveCourses');


    //student of courses
    $router->get('/student-of-courses?{id}', 'StudentOfCoursesController@index');

    $router->post('/get-data-table-student-of-courses', 'StudentOfCoursesController@searchStudent');
    $router->post('/student-of-courses-add', 'StudentOfCoursesController@createStudent');
    $router->post('/student-update-score', 'StudentOfCoursesController@updateStudent');
    $router->post('/student-of-courses-delete/{id}', 'StudentOfCoursesController@deleteStudent');

    //users
    $router->get('/users-list', 'UsersController@index');
    $router->post('/get-data-table-users', 'UsersController@getDatatable');
    $router->post('/users-add', 'UsersController@createUsers');

    $router->get('/login', 'UsersController@loginPage');
    $router->post('/login', 'UsersController@login');

