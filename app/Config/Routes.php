<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('admin/login', 'Admin::login');
$routes->post('admin/login', 'Admin::loginSubmit');
$routes->get('admin/register', 'Admin::register');
$routes->post('admin/register', 'Admin::registerSubmit');
$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('admin/settings', 'Admin::settings');
$routes->post('admin/settings/password', 'Admin::changePassword');
$routes->get('admin/students/create', 'Admin::createStudent');
$routes->post('admin/students/store', 'Admin::storeStudent');
$routes->get('admin/students/edit/(:num)', 'Admin::editStudent/$1');
$routes->post('admin/students/update/(:num)', 'Admin::updateStudent/$1');
$routes->post('admin/students/delete/(:num)', 'Admin::deleteStudent/$1');
$routes->get('admin/logout', 'Admin::logout');

$routes->get('student/login', 'Student::login');
$routes->post('student/login', 'Student::loginSubmit');
$routes->get('student/profile', 'Student::profile');
$routes->post('student/profile/update', 'Student::updateProfile');
$routes->post('student/certificates/delete/(:num)', 'Student::deleteCertificate/$1');
$routes->get('student/logout', 'Student::logout');

$routes->post('files/photo/(:num)', 'Files::uploadPhoto/$1');
$routes->post('files/certificates/(:num)', 'Files::uploadCertificates/$1');
$routes->get('files/photo/view/(:num)', 'Files::viewPhoto/$1');
$routes->get('files/certificate/view/(:num)', 'Files::viewCertificate/$1');

$routes->get('dbtest', 'Home::dbtest');
$routes->get('home/dbtest', 'Home::dbtest');
