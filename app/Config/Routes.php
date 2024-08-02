<?php


namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Router\Router;
use App\Controllers\UserRoleController;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AdminController');
$routes->setDefaultMethod('default');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);


use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('', 'AdminController::default', ['as' => 'admin.default']);

$routes->group('admin', static function ($routes) {
    $routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
        //$routes->view('example-page','example-page');
        $routes->get('home', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('logout', 'AdminController::logoutHandler', ['as' => 'admin.logout']);
        $routes->get('dashboard', 'AdminController::dashboard', ['as' => 'admin.dashboard']);
        $routes->get('get-users', 'AdminController::getUsers', ['as' => 'get-users']);
        $routes->get('new-user', 'AdminController::addUser', ['as' => 'new-user']);
        $routes->post('create-user', 'AdminController::createUser', ['as' => 'create-user']);
        //$routes->get('users/edit/(:num)', 'AdminController::editUser/$1', ['as' => 'admin.users.edit']);
        //$routes->post('users\delete/(:num)', 'AdminController::deleteUser/$1', ['as' => 'admin.users.delete']);
        //$routes->get('users/get/(:num)', 'AdminController::getUser/$1', ['as' => 'admin.users.get']);
        $routes->post('get-users', 'AdminController::getUser', ['as' => 'admin.users.get']);
        $routes->get('user/edit', 'AdminController::edit', ['as' => 'user.edit']);
        $routes->post('user/update', 'AdminController::update', ['as' => 'user.update']);
        $routes->post('user/delete', 'AdminController::delete', ['as' => 'user.delete']);
        $routes->get('profile', 'AdminController::profile', ['as' => 'profile']);
        $routes->get('logs', 'LogsController::index');
         $routes->get('change-password', 'AdminController::changePassword', ['as' => 'change_password']);
        $routes->post('change-password', 'AdminController::updatePassword');
        

    });

    $routes->group('tickets',['filter' => 'cifilter:auth'], static function ($routes) {
        $routes->get('new-ticket', 'AdminController::addTicket', ['as' => 'new-ticket']);
        $routes->post('create-ticket', 'AdminController::createTicket', ['as' => 'create-ticket']);
        $routes->post('post-reply', 'AdminController::postReply', ['as' => 'post-reply']);
        $routes->get('my-tickets', 'AdminController::myTickets', ['as' => 'my-tickets']);
        $routes->get('assign', 'TicketController::showAssignForm', ['as' => 'show_assign_form']);
        $routes->post('assign', 'TicketController::assignTicket', ['as' => 'assign_ticket']);
        $routes->get('assigned', 'TicketController::assignedTickets', ['as' => 'assigned_ticket']);
        $routes->post('get-open-tickets-by-category', 'TicketController::getOpenTicketsByCategory');
        $routes->get('ticket-details/(:num)', 'AdminController::viewTicket/$1');
        $routes->post('search', 'AdminController::search');
        $routes->post('my-tickets-search', 'AdminController::searchMyTickets');
        $routes->post('search-tickets', 'AdminController::searchTicketsInCategories');
        $routes->post('addRemarks', 'TicketController::addRemarks');
        $routes->get('reports', 'TicketController::reportsTickets');
        $routes->post('close/(:num)', 'TicketController::closeTicket/$1');
        $routes->post('reopen/(:num)', 'TicketController::reopenTicket/$1');

    });
    
    $routes->group('categories',['filter' => 'cifilter:auth'], static function ($routes) {
        $routes->get('tickets/(:num)', 'AdminController::viewTicketsByCategory/$1');
        $routes->get('add-categories', 'AdminController::categories', ['as' => 'categories']);
        $routes->post('add-category', 'AdminController::addCategory', ['as' => 'add-category']);
        $routes->get('get-categories', 'AdminController::getCategories', ['as' => 'get-categories']);
        $routes->get('edit-category/(:num)', 'AdminController::editCategory/$1');
        $routes->post('update-category/(:num)', 'AdminController::updateCategory/$1');
        $routes->get('get-category', 'AdminController::getCategory', ['as' => 'get-category']);
        $routes->post('update-category', 'AdminController::updateCategory', ['as' => 'update-category']);
        $routes->post('delete-category', 'AdminController::deleteCategory', ['as' => 'delete-category']);
      
    });


    $routes->group('roles', ['filter' => 'cifilter:auth'], function ($routes) {
        $routes->get('/', 'RolesController::index');
        $routes->post('create', 'RolesController::create');
        $routes->get('edit/(:num)', 'RolesController::edit/$1');
        $routes->post('update/(:num)', 'RolesController::update/$1');
        $routes->get('delete/(:num)', 'RolesController::delete/$1');
    });
    
  
    $routes->group('', ['filter' => 'cifilter:guest'], static function ($routes) {
        //$routes->view('example-auth','example-auth');
        $routes->get('login', 'AuthController::loginForm', ['as' => 'admin.login.form']);
        $routes->post('login', 'AuthController::loginHandler', ['as' => 'admin.login.handler']);
        $routes->get('forgot', 'AuthController::forgotPassword', ['as' => 'admin.forgot.form']);
        //$routes->get('forgot-password', 'AuthController::forgotPassword', ['as' => 'admin.forgot.form']);
        $routes->post('forgot-password', 'AuthController::sendResetLink', ['as' => 'send_password_reset_link']);
        //$routes->post('send-password-reset-link','AuthController::sendPasswordResetLink',['as'=>'send_password_reset_link']);
        $routes->get('password/reset/(:any)', 'AuthController::resetPassword/$1', ['as' => 'admin.reset_password']);
    });

});
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

