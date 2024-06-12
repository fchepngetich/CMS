<?php


namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Router\Router;
use App\Controllers\UserRoleController;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'Home::index');
$routes->get('/userroles', 'UserRoleController::index');
$routes->post('/userroles/store', 'UserRoleController::store');
$routes->get('/userroles/create', 'UserRoleController::create');
$routes->get('/userroles/edit/(:num)', 'UserRoleController::edit/$1');
$routes->post('/userroles/update/(:num)', 'UserRoleController::update/$1');
$routes->get('/userroles/delete/(:num)', 'UserRoleController::delete/$1');
$routes->post('/tickets/close/(:num)', 'TicketController::closeTicket/$1');
$routes->post('/tickets/reopen/(:num)', 'TicketController::reopenTicket/$1');





use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('admin',static function($routes){

$routes->group('',['filter'=>'cifilter:auth'],static function($routes){
    //$routes->view('example-page','example-page');
    $routes->get('home','AdminController::index',['as'=>'admin.home']);
    $routes->get('logout','AdminController::logoutHandler',['as'=>'admin.logout']);
    $routes->get('categories','AdminController::categories',['as'=>'categories']);
    $routes->post('add-category','AdminController::addCategory',['as'=>'add-category']);
    $routes->get('get-categories','AdminController::getCategories',['as'=>'get-categories']);
    $routes->get('get-category','AdminController::getCategory',['as'=>'get-category']);
    $routes->post('update-category','AdminController::updateCategory',['as'=>'update-category']);
    $routes->get('delete-category','AdminController::deleteCategory',['as'=>'delete-category']);
    $routes->get('reorder-categories','AdminController::reorderCategories',['as'=>'reorder-categories']);
    $routes->get('get-parent-categories','AdminController::getParentCategories',['as'=>'get-parent-categories']);
    $routes->post('add-subcategory','AdminController::addSubCategory',['as'=>'add-subcategory']);
    $routes->get('get-subcategories','AdminController::getSubCategories',['as'=>'get-subcategories']);
    $routes->get('get-subcategory','AdminController::getSubCategory',['as'=>'get-subcategory']);
    $routes->post('update-subcategory','AdminController::updateSubCategory',['as'=>'update-subcategory']);
    $routes->get('reorder-subcategories','AdminController::reorderSubCategories',['as'=>'reorder-subcategories']);
    $routes->get('delete-subcategory','AdminController::deleteSubCategory',['as'=>'delete-subcategory']);
    $routes->get('dashboard', 'AdminController::dashboard', ['as' => 'admin.dashboard']);
    $routes->get('get-users','AdminController::getUsers',['as'=>'get-users']);
    $routes->get('new-user','AdminController::addUser',['as'=>'new-user']);
    $routes->post('create-user','AdminController::createUser',['as'=>'create-user']);
    //$routes->get('users/edit/(:num)', 'AdminController::editUser/$1', ['as' => 'admin.users.edit']);
    //$routes->post('users\delete/(:num)', 'AdminController::deleteUser/$1', ['as' => 'admin.users.delete']);
    //$routes->get('users/get/(:num)', 'AdminController::getUser/$1', ['as' => 'admin.users.get']);
   $routes->post('get-users', 'AdminController::getUser', ['as' => 'admin.users.get']);
   $routes->post('admin/users/update', 'AdminController::updateUser', ['as' => 'admin.users.update']);



});

$routes->group('tickets',static function($routes){
        
    $routes->get('new-ticket','AdminController::addTicket',['as'=>'new-ticket']);
    $routes->post('create-ticket','AdminController::createTicket',['as'=>'create-ticket']);
    $routes->post('post-reply','AdminController::postReply',['as'=>'post-reply']);
    $routes->get('my-tickets','AdminController::myTickets',['as'=>'my-tickets']);


});


$routes->group('roles', function ($routes) {
    $routes->get('/', 'RolesController::index', ['as' => 'admin.roles.index']);
    $routes->get('create', 'RolesController::create', ['as' => 'admin.roles.create']);
    $routes->get('roles', 'RolesController::getRoles', ['as' => 'admin.roles.getRoles']);

    $routes->post('create', 'RolesController::store');
    $routes->get('edit/(:num)', 'RolesController::edit/$1', ['as' => 'admin.roles.edit']);
    $routes->post('edit/(:num)', 'RolesController::update/$1');
    $routes->get('delete/(:num)', 'RolesController::delete/$1', ['as' => 'admin.roles.delete']);


    $routes->get('/userroles', 'UserRoleController::index');
$routes->get('/userroles/create', 'UserRoleController::create');
$routes->post('/userroles/store', 'UserRoleController::store');
$routes->get('/userroles/edit/(:num)', 'UserRoleController::edit/$1');
$routes->post('/userroles/update/(:num)', 'UserRoleController::update/$1');
$routes->get('/userroles/delete/(:num)', 'UserRoleController::delete/$1');

});

$routes->group('',['filter'=>'cifilter:guest'],static function($routes){
    //$routes->view('example-auth','example-auth');
    $routes->get('login','AuthController::loginForm',['as'=>'admin.login.form']);
    $routes->post('login','AuthController::loginHandler',['as'=>'admin.login.handler']);
    $routes->get('forgot','AuthController::forgotForm',['as'=>'admin.forgot.form']);
    $routes->post('send-password-reset-link','AuthController::sendPasswordResetLink',['as'=>'send_password_reset_link']);
    $routes->get('password/reset/(:any)','AuthController::resetPassword/$1',['as'=>'admin.reset_password']);
});

});
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

