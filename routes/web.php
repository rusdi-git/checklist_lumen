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

#Checklist
$router->get('/checklists','ChecklistController@index');
$router->post('/checklists','ChecklistController@store');
$router->get('/checklists/{id:[0-9]+}','ChecklistController@show');
$router->patch('/checklists/{id:[0-9]+}','ChecklistController@edit');
$router->delete('/checklists/{id:[0-9]+}','ChecklistController@remove');

#Checklist Item
$router->get('/checklists/{checklistid:[0-9]+}/items','ItemController@index');
$router->post('/checklists/{checklistid:[0-9]+}/items','ItemController@store');
$router->get('/checklists/{checklistid:[0-9]+}/items/{itemid:[0-9]+}','ItemController@show');
$router->patch('/checklists/{checklistid:[0-9]+}/items/{itemid:[0-9]+}','ItemController@edit');
$router->delete('/checklists/{checklistid:[0-9]+}/items/{itemid:[0-9]+}','ItemController@remove');

#Template
$router->get('/checklists/templates','TemplateController@index');
$router->post('/checklists/templates','TemplateController@store');
$router->get('/checklists/templates/{templateid:[0-9]+}','TemplateController@show');
$router->patch('/checklists/templates/{templateid:[0-9]+}','TemplateController@edit');
$router->delete('/checklists/templates/{templateid:[0-9]+}','TemplateController@remove');

#Login
$router->post('/authenticate','UserController@authenticate');