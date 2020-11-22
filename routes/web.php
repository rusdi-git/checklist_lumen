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
$router->get('/checklists/{id}','ChecklistController@show');
$router->patch('/checklists/{id}','ChecklistController@edit');
$router->delete('/checklists/{id}','ChecklistController@remove');

#Checklist Item
$router->get('/checklists/{checklistid}/items','ItemController@index');
$router->post('/checklists/{checklistid}/items','ItemController@store');
$router->get('/checklists/{checklistid}/items/{itemid}','ItemController@show');
$router->patch('/checklists/{checklistid}/items/{itemid}','ItemController@edit');
$router->delete('/checklists/{checklistid}/items/{itemid}','ItemController@remove');