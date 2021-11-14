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

//courses page

$router->get('/courses-list', 'CoursesController@index');
$router->post('/get-data-table-courses', 'CoursesController@getDatatable');

$router->post('/courses-add', 'CoursesController@createCourses');




$router->get('/student-of-courses', 'StudentOfCoursesController@index');

$router->post('/get-data-table-student-of-courses', 'StudentOfCoursesController@searchStudent');


