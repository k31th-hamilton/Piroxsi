<?php

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

$app->get('/', [
  'as' => 'piroxsi.resourcelist',
  'uses' => 'PiroxsiController@ResourceList'
]);
  
$app->get('/refresh', [
  'as' => 'piroxsi.refresh',
  'uses' => 'PiroxsiController@RefreshResourceList'
]);

$app->get('/download/{id}', [
  'as' => 'piroxsi.download',
  'uses' => 'PiroxsiController@DownloadResourceInfo'
]);

$app->get('/status/{id}', [
  'as' => 'piroxsi.status',
  'uses' => 'PiroxsiController@ResourceStatus'
]);

$app->get('/refresh/{command}', [
  'as' => 'piroxsi.refresh',
  'uses' => 'PiroxsiController@ResourceRefresh'
]);