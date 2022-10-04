<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Create/Signup a new user and generate a new API token.
$router->post('/register', 'AuthController@register');

// User's login function. It generates an API token when user logs in.
$router->put('/login', 'AuthController@login');

// User's logout function. It removes the API token when user logs out.
$router->put('/logout', 'AuthController@logout');

// The logged in user is authenticated via the auth middleware used in the controller constructor.

// User Management
$router->get('/users', 'UserController@index');
$router->get('/users/create', 'UserController@create');
$router->post('/users', 'UserController@store');
$router->get('/users/{id}', 'UserController@show');
$router->get('/users/{id}/edit', 'UserController@edit');
$router->put('/users/{id}', 'UserController@update');
$router->delete('/users/{id}', 'UserController@destroy');

// List all todo notes for the logged in user.
$router->get('/todonotes', 'TodoNoteController@index');

// List all todo notes for an arbitrary user
$router->get('/todonotes/user/{userId}', 'TodoNoteController@arbitraryIndex');

// Create a new todo note. And assign to the logged in user.
$router->post('/todonotes', 'TodoNoteController@store');

// Display the specified todo note.
$router->get('/todonotes/{id}', 'TodoNoteController@show');

// Delete a new todo note. Only on todo notes that the logged in user owns.
$router->delete('/todonotes/{id}', 'TodoNoteController@destroy');

// Mark a todo note as complete.
$router->put('/todonotes/{id}/complete', 'TodoNoteController@complete');

// Mark a todo note as incomplete.
$router->put('/todonotes/{id}/incomplete', 'TodoNoteController@incomplete');

